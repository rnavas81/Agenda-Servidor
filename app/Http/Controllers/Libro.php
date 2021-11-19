<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Coche;
use App\Models\Conductor;
use App\Models\Libro as ModelsLibro;
use App\Models\LibroCoches;
use App\Models\LibroConductores;
use App\Models\LibroHistorico;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Libro extends Controller
{
    public function getAll(Request $request)
    {
        $entradas = $this->getDB();
        return response()->json($entradas, 200);
    }
    public function get(Request $request, $id)
    {
        if ($id == 0) $entrada = new Libro();
        else $entrada = $this->getDB(['id' => $id], 1);
        if (!empty($entrada))
            return response()->json($entrada, 200);
        else
            return response()->noContent(204);
    }
    public function getByFecha(Request $request, $fecha)
    {
        $entradas = $this->getDB(['salidaFecha' => $fecha]);
        return response()->json($entradas, 200);
    }
    public function getSemana(Request $request, $fecha)
    {
        $f = new DateTime($fecha);
        $dia = (int)$f->format('N') - 1;
        $f->sub(new DateInterval('P' . $dia . 'D'));
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $data[$f->format("Y-m-d")] = $this->getDB(['salidaFecha' => $f, "confirmada" => false]);
            $f->add(new DateInterval('P1D'));
        }
        return response()->json($data, 200);
    }
    public function insert(Request $request)
    {
        try {
            $data = $this->getRequestData($request);
            if ($request->user()) {
                $data['idUsuario'] = $request->user()->id;
            } else {
                $data['idUsuario'] = 0;
            }
            DB::beginTransaction();
            $nuevo = $this->insertDB($data);
            if ($nuevo) {
                $coches = isset($request['coches']) ? $request['coches'] : [];
                $this->addCoches($nuevo->id, $coches);
                $conductores = isset($request['conductores']) ? $request['conductores'] : [];
                $this->addConductores($nuevo->id, $conductores);
                DB::commit();
                return response()->json($nuevo, 201);
            } else {
                throw new Exception("Error al crear nueva entrada en el libro", 1);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return response()->noContent(406);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $this->getRequestData($request);
            DB::beginTransaction();
            $entrada = $this->updateDB($id, $data);
            if ($entrada) {
                $coches = isset($request['coches']) ? $request['coches'] : [];
                $this->addCoches($entrada->id, $coches);
                $conductores = isset($request['conductores']) ? $request['conductores'] : [];
                $this->addConductores($entrada->id, $conductores);
                DB::commit();
                return response()->json($entrada, 201);
            } else {
                throw new Exception("Error al modificar la entrada del libro", 1);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->noContent(406);
        }
    }
    /**
     * Elimina una entrada
     */
    public function delete(Request $request, $id)
    {
        if ($this->deleteDB($id)) {
            return response()->noContent(200);
        } else {
            return response()->noContent(406);
        }
    }

    public function getHistorico(Request $request, $id)
    {
        $data = LibroHistorico::where("id",$id)->first();
        if($data){
            $data->coches=explode("##",$data->coches);
            $data->conductores=explode("##",$data->conductores);
            return response()->json($data,200);
        } 
        else return response()->noContent(406);
    }

    /**
     * Recupera información de las entradas de la base de datos
     * @param Integer $id identificador de la entrada
     */
    public function getDB($where = [], $cuantas = false)
    {
        $entradas = ModelsLibro::with('usuario', 'cliente', 'coches.coche', 'conductores.conductor')->where('habilitado', 1);
        if (isset($where['id'])) $entradas = $entradas->where('id', $where['id']);
        if (isset($where['salidaFecha'])) $entradas = $entradas->where('salidaFecha', $where['salidaFecha']);
        $entradas = $entradas->orderBy('salidaFecha')
            ->orderBy('salidaHora')
            ->orderBy('llegadaFecha')
            ->orderBy('llegadaHora');
        if (!$cuantas) {
            $entradas = $entradas->get();
        } elseif ($cuantas == 1) {
            $entradas = $entradas->first();
        }
        return $entradas;
    }
    /**
     * Crea una nueva entrada en la base de datos
     */
    public function insertDB($data)
    {
        $nuevo = ModelsLibro::create($data);
        return $nuevo;
    }
    /**
     * Actualiza los datos de una entrada en la base de datos
     */
    public function updateDB($id, $data)
    {
        $update = ModelsLibro::where('id', $id)->update($data);
        if ($update > 0) {
            return $this->getDB(['id' => $id], 1);
        } else {
            return false;
        }
    }
    /**
     * Elimina una entrada de la base de datos
     */
    public function deleteDB($id)
    {
        return ModelsLibro::where('id', $id)->update([
            'habilitado' => 0
        ]) == 1;
    }
    /**
     * Elimina los registros previos
     * Inserta los coches asignados
     */
    public function addCoches($idLibro, $idsCoches)
    {
        LibroCoches::where('idLibro', $idLibro)->delete();
        foreach ($idsCoches as $idCoche) {
            if (!is_int($idCoche)) {
                $coche = Coche::where('matricula', $idCoche)->first();
                if ($coche) {
                    $idCoche = $coche->id;
                } else {
                    $coche = Coche::create([
                        'matricula' => $idCoche
                    ]);
                    $idCoche = $coche->id;
                }
            }
            LibroCoches::create([
                'idLibro' => $idLibro, 'idCoche' => $idCoche
            ]);
        }
    }
    /**
     * Elimina los registros previos
     * Inserta los conductores asignados
     */
    public function addConductores($idLibro, $idsConductores)
    {
        LibroConductores::where('idLibro', $idLibro)->delete();
        foreach ($idsConductores as $idConductor) {
            if (!is_int($idConductor)) {
                $conductor = Conductor::where('nombre', $idConductor)->first();
                if ($conductor) {
                    $idConductor = $conductor->id;
                } else {
                    $conductor = Conductor::create([
                        'nombre' => $idConductor
                    ]);
                    $idConductor = $conductor->id;
                }
            }
            LibroConductores::create([
                'idLibro' => $idLibro, 'idConductor' => $idConductor
            ]);
        }
    }
    public function getRequestData(Request $request)
    {
        if (isset($request['salidaFecha'])) $data['salidaFecha'] = $request['salidaFecha'];
        if (isset($request['salidaHora'])) $data['salidaHora'] = $request['salidaHora'];
        if (isset($request['salidaLugar'])) $data['salidaLugar'] = $request['salidaLugar'];
        if (isset($request['llegadaFecha'])) $data['llegadaFecha'] = $request['llegadaFecha'];
        if (isset($request['llegadaHora'])) $data['llegadaHora'] = $request['llegadaHora'];
        if (isset($request['llegadaLugar'])) $data['llegadaLugar'] = $request['llegadaLugar'];
        if (isset($request['itinerario'])) $data['itinerario'] = $request['itinerario'];
        if (isset($request['kms'])) $data['kms'] = $request['kms'];
        if (isset($request['cliente'])) {
            if($request['cliente']==null)$data['idCliente']=null;
            else {
                $cliente = Cliente::where('id', $request['cliente']['id'])->first();
                if (!$cliente) {
                    $cliente = Cliente::create([
                        'nombre' => $request['cliente']['nombre'],
                        'telefono' => $request['cliente']['telefono']
                    ]);
                }
                $data['idCliente'] = $cliente['id'];
            }
        }
        if (isset($request['idCliente'])) $data['idCliente'] = $request['idCliente'];
        if (isset($request['clienteDetalle'])) $data['clienteDetalle'] = $request['clienteDetalle'];
        if (isset($request['observaciones'])) $data['observaciones'] = $request['observaciones'];
        if (isset($request['facturarA'])) $data['facturarA'] = $request['facturarA'];
        if (isset($request['contacto'])) $data['contacto'] = $request['contacto'];
        if (isset($request['contactoTlf'])) $data['contactoTlf'] = $request['contactoTlf'];
        if (isset($request['importe'])) $data['importe'] = $request['importe'];
        if (isset($request['presupuesto'])) $data['importe'] = $request['presupuesto'];
        if (isset($request['cobrado'])) $data['cobrado'] = $request['cobrado'];
        if (isset($request['cobradoFecha'])) $data['cobradoFecha'] = $request['cobradoFecha'];
        if (isset($request['cobradoForma'])) $data['cobradoForma'] = $request['cobradoForma'];
        if (isset($request['cobradoDetalle'])) $data['cobradoDetalle'] = $request['cobradoDetalle'];
        if (isset($request['gastos'])) $data['gastos'] = $request['gastos'];
        if (isset($request['facturaNombre'])) $data['facturaNombre'] = $request['facturaNombre'];
        if (isset($request['facturaNumero'])) $data['facturaNumero'] = $request['facturaNumero'];
        return $data;
    }
}

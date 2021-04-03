<?php

namespace App\Http\Controllers;

use App\Models\Aviso as ModelsAviso;
use App\Models\AvisoCoches;
use App\Models\AvisoConductores;
use App\Models\Cliente;
use App\Models\Coche;
use App\Models\Conductor;
use App\Models\Libro;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Aviso extends Controller
{
    public function getAll(Request $request)
    {
        $entradas = $this->getDB();
        return response()->json($entradas, 200);
    }
    public function get(Request $request, $id)
    {
        if ($id == 0) $entrada = new Aviso();
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
            $data[$f->format("Y-m-d")] = $this->getDB(['salidaFecha' => $f]);
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
                throw new Exception("Error al crear nuevo aviso", 1);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
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
                throw new Exception("Error al modificar el aviso", 1);
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
    /**
     * Pasa un aviso al libro
     */
    public function confirm(Request $request, $id)
    {
        // Actualiza los datos de la entrada
        $entrada = $this->updateDB($id, ['confirmada' => 1]);
        if ($entrada) {
            try {
                $data = $entrada->toArray();
                $extra = $this->toValidArray($data);
                $data["idUsuario"] = $request->user()->id;
                DB::beginTransaction();
                $nuevo = app(Libro::class)->insertDB($data);
                if ($nuevo) {
                    app(Libro::class)->addCoches($nuevo->id, $extra["coches"]);
                    app(Libro::class)->addConductores($nuevo->id, $extra["conductores"]);
                    DB::commit();
                    return response()->json($nuevo, 201);
                } else {
                    throw new Exception("Error al crear nueva entrada en el libro", 1);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->noContent(406);
            }
        } else {
            return response()->noContent(204);
        }
    }
    /**
     * Recupera información de las entradas de la base de datos
     * @param Integer $id identificador de la entrada
     */
    public function getDB($where = [], $cuantas = false)
    {
        $entradas = ModelsAviso::with('usuario', 'cliente', 'coches.coche', 'conductores.conductor')->where('habilitado', 1);
        if (isset($where['id'])) $entradas = $entradas->where('id', $where['id']);
        if (isset($where['salidaFecha'])) $entradas = $entradas->where('salidaFecha', $where['salidaFecha']);
        if (isset($where['confirmada'])) {
            if($where['confirmada'])$entradas = $entradas->where('confirmada', 1);
            else $entradas = $entradas->where('confirmada', 0);
        }
        $entradas = $entradas->orderBy('salidaFecha')
            ->orderBy('salidaHora')
            ->orderBy('llegadaFecha')
            ->orderBy('llegadaHora')
            ->orderBy('confirmada');
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
        $nuevo = ModelsAviso::create($data);
        return $nuevo;
    }
    /**
     * Actualiza los datos de una entrada en la base de datos
     */
    public function updateDB($id, $data)
    {
        $update = ModelsAviso::where('id', $id)->where('habilitado', 1)->update($data);
        if ($update == 1) {
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
        return ModelsAviso::where('id', $id)->update([
            'habilitado' => 0
        ]) == 1;
    }
    /**
     * Elimina los registros previos
     * Inserta los coches asignados
     */
    public function addCoches($idAviso, $idsCoches)
    {
        AvisoCoches::where('idAviso', $idAviso)->delete();
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
            AvisoCoches::create([
                'idAviso' => $idAviso, 'idCoche' => $idCoche
            ]);
        }
    }
    /**
     * Elimina los registros previos
     * Inserta los conductores asignados
     */
    public function addConductores($idAviso, $idsConductores)
    {
        AvisoConductores::where('idAviso', $idAviso)->delete();
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
            AvisoConductores::create([
                'idAviso' => $idAviso, 'idConductor' => $idConductor
            ]);
        }
    }
    /**
     * Recupera los datos enviados
     */
    public function getRequestData(Request $request)
    {
        $data = [];
        if (isset($request['salidaFecha'])) $data['salidaFecha'] = $request['salidaFecha'];
        if (isset($request['salidaHora'])) $data['salidaHora'] = $request['salidaHora'];
        if (isset($request['salidaLugar'])) $data['salidaLugar'] = $request['salidaLugar'];
        if (isset($request['llegadaFecha'])) $data['llegadaFecha'] = $request['llegadaFecha'];
        if (isset($request['llegadaHora'])) $data['llegadaHora'] = $request['llegadaHora'];
        if (isset($request['llegadaLugar'])) $data['llegadaLugar'] = $request['llegadaLugar'];
        if (isset($request['itinerario'])) $data['itinerario'] = $request['itinerario'];
        if (isset($request['idCliente'])) $data['idCliente'] = $request['idCliente'];
        if (isset($request['cliente'])) {
            $cliente = Cliente::where('id', $request['cliente']['id'])->first();
            if (!$cliente) {
                $cliente = Cliente::create([
                    'nombre' => $request['cliente']['nombre'],
                    'telefono' => $request['cliente']['telefono']
                ]);
            }
            $data['idCliente'] = $request['cliente']['id'];
        }
        if (isset($request['clienteDetalle'])) $data['clienteDetalle'] = $request['clienteDetalle'];
        if (isset($request['presupuesto'])) $data['presupuesto'] = $request['presupuesto'];
        return $data;
    }
    /**
     * Formatea el array con los campos validdos
     */
    public function toValidArray(&$data)
    {
        $data['idAviso'] = $data["id"];
        if (isset($data['cliente'])) $data['idCliente'] = $data['cliente']['id'];
        $extra = [
            "coches" => [], "conductores" => []
        ];
        foreach ($data['coches'] as $coche) {
            $extra["coches"][] = $coche["coche"]['id'];
        }
        foreach ($data['conductores'] as $conductor) {
            $extra["conductores"][] = $conductor["conductor"]['id'];
        }
        unset($data["id"], $data['cliente'], $data['usuario'], $data["conductores"], $data["coches"]);
        return $extra;
    }
}

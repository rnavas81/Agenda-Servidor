<?php

namespace App\Http\Controllers;

use App\Models\AgendaEntrada;
use App\Models\LibroEntrada;
use Illuminate\Http\Request;

class Agenda extends Controller
{
    public function getAll(Request $request)
    {
        $entradas = $this->getDB();
        return response()->json($entradas, 200);
    }
    public function get(Request $request, $id)
    {
        $entrada = $this->getDB('id', $id);
        if (!empty($entrada))
            return response()->json($entrada, 200);
        else
            return response()->noContent(204);
    }
    public function getByFecha(Request $request, $fecha)
    {
        $entradas = $this->getDB('fecha', $fecha);
        return response()->json($entradas, 200);
    }
    public function insert(Request $request)
    {
        $data = [];
        if ($request->user()) {
            $data['idUsuario'] = $request->user()->id;
        } else {
            $data['idUsuario'] = 0;
        }
        if (isset($request['salidaFecha'])) $data['salidaFecha'] = $request['salidaFecha'];
        if (isset($request['salidaHora'])) $data['salidaHora'] = $request['salidaHora'];
        if (isset($request['salidaLugar'])) $data['salidaLugar'] = $request['salidaLugar'];
        if (isset($request['llegadaFecha'])) $data['llegadaFecha'] = $request['llegadaFecha'];
        if (isset($request['llegadaHora'])) $data['llegadaHora'] = $request['llegadaHora'];
        if (isset($request['llegadaLugar'])) $data['llegadaLugar'] = $request['llegadaLugar'];
        if (isset($request['itinerario'])) $data['itinerario'] = $request['itinerario'];
        if (isset($request['idCliente'])) $data['idCliente'] = $request['idCliente'];
        if (isset($request['clienteDetalle'])) $data['clienteDetalle'] = $request['clienteDetalle'];
        if (isset($request['presupuesto'])) $data['presupuesto'] = $request['presupuesto'];
        $nuevo = $this->insertDB($data);
        if ($nuevo) {
            return response()->json($nuevo, 201);
        } else {
            return response()->noContent(406);
        }
    }
    public function update(Request $request, $id)
    {
        $data = [];
        if ($request->user()) {
            $data['idUsuario'] = $request->user()->id;
        } else {
            $data['idUsuario'] = 0;
        }
        if (isset($request['salidaFecha'])) $data['salidaFecha'] = $request['salidaFecha'];
        if (isset($request['salidaHora'])) $data['salidaHora'] = $request['salidaHora'];
        if (isset($request['salidaLugar'])) $data['salidaLugar'] = $request['salidaLugar'];
        if (isset($request['llegadaFecha'])) $data['llegadaFecha'] = $request['llegadaFecha'];
        if (isset($request['llegadaHora'])) $data['llegadaHora'] = $request['llegadaHora'];
        if (isset($request['llegadaLugar'])) $data['llegadaLugar'] = $request['llegadaLugar'];
        if (isset($request['itinerario'])) $data['itinerario'] = $request['itinerario'];
        if (isset($request['idCliente'])) $data['idCliente'] = $request['idCliente'];
        if (isset($request['clienteDetalle'])) $data['clienteDetalle'] = $request['clienteDetalle'];
        if (isset($request['presupuesto'])) $data['presupuesto'] = $request['presupuesto'];
        $entrada = $this->updateDB($id, $data);
        if ($entrada) {
            return response()->json($entrada, 201);
        } else {
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
     * Pasa una entrada de la agenda al libro
     */
    public function confirm(Request $request, $id)
    {
        // Actualiza los datos de la entrada
        $entrada = $this->updateDB($id,['confirmada'=>1]);
        if($entrada){
            // TODO: Insertar nueva entrada en el libro
            // $nueva=app('App\Http\Controllers\Libro')->insertDB($entrada);
            // if($nueva){
            //     return response()->json($nueva, 200);
            // } else {
            //     return response()->noContent(406);
            // }
        } else {
            return response()->noContent(204);
        }
    }
    /**
     * Recupera información de las entradas de la base de datos
     * @param Integer $id identificador de la entrada
     */
    public function getDB($type = null, $param = null)
    {
        $entradas = AgendaEntrada::with('usuario', 'cliente', 'coches.coche', 'conductores.conductor')->where('habilitado', 1)->where('confirmada', 0);
        switch ($type) {
            case 'id':
                $entradas = $entradas->where('id', $param)->first();
                break;
            case 'fecha':
                $entradas = $entradas->where('salidaFecha', $param)->orderBy('salidaHora')->get();
                break;

            default:
                $entradas = $entradas->orderBy('salidaFecha')->orderBy('salidaHora')->get();
                break;
        }
        return $entradas;
    }
    /**
     * Crea una nueva entrada en la base de datos
     */
    public function insertDB($data)
    {
        $nuevo = AgendaEntrada::create($data);
        return $nuevo;
    }
    /**
     * Actualiza los datos de una entrada en la base de datos
     */
    public function updateDB($id, $data)
    {
        $update = AgendaEntrada::where('id', $id)->update($data);
        if ($update > 0) {
            return $this->getDB('id', $id);
        } else {
            return false;
        }
    }
    /**
     * Elimina una entrada de la base de datos
     */
    public function deleteDB($id)
    {
        return AgendaEntrada::where('id', $id)->update([
            'habilitado' => 0
        ]) == 1;
    }
}

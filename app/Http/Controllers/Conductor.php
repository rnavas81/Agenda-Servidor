<?php

namespace App\Http\Controllers;

use App\Models\Conductor as ModelsConductor;
use Illuminate\Http\Request;

class Conductor extends Controller
{
    public function getAll(Request $request)
    {
        $entradas = $this->getDB();
        return response()->json($entradas, 200);
    }
    public function get(Request $request, $id)
    {
        $entrada = $this->getDB(['id' => $id], 1);
        if (!empty($entrada))
            return response()->json($entrada, 200);
        else
            return response()->noContent(204);
    }
    public function insert(Request $request)
    {
        $data = $this->getRequestData($request);
        $nuevo = $this->insertDB($data);
        if ($nuevo) {
            return response()->json($nuevo, 201);
        } else {
            return response()->noContent(406);
        }
    }
    public function update(Request $request, $id)
    {
        $data = $this->getRequestData($request);
        $entrada = $this->updateDB($id, $data);
        if ($entrada) {
            return response()->json($entrada, 200);
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
     * Recupera información de las entradas de la base de datos
     * @param Integer $id identificador de la entrada
     */
    public function getDB($where = [], $cuantas = false)
    {
        $entradas = ModelsConductor::where('habilitado', 1);
        if (isset($where['id'])) $entradas = $entradas->where('id', $where['id']);
        $entradas = $entradas->orderBy('nombre');
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
        $nuevo = ModelsConductor::create($data);
        return $nuevo;
    }
    /**
     * Actualiza los datos de una entrada en la base de datos
     */
    public function updateDB($id, $data)
    {
        $update = ModelsConductor::where('id', $id)->update($data);
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
        return ModelsConductor::where('id', $id)->update([
            'habilitado' => 0
        ]) == 1;
    }
    public function getRequestData(Request $request)
    {
        $data = [];
        if (isset($request['nombre'])) $data['nombre'] = $request['nombre'];
        return $data;
    }
}

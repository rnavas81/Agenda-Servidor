<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{
    public function insert(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'lastName'  => ['string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:32'],
            'contact' => ['string', 'max:32']
        ]);
        try {
            $data = $this->getRequestData($request);
            DB::beginTransaction();
            $nuevo = $this->insertDB($data);
            if ($nuevo) {
                // if (isset($request['rol'])) {
                //     $this->addRol($nuevo->id, $request['rol']);
                // }
                DB::commit();
                return response()->json($nuevo, 201);
            } else {
                throw new Exception('Error al crear nuevo usuario', 1);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->noContent(406);
        }
    }
    public function update(Request $request, $id = null)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'lastName'  => ['string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['string', 'min:8', 'max:32'],
        ]);
        $id != null or $id = auth()->user()->id;
        try {
            $data = $this->getRequestData($request);
            DB::beginTransaction();
            $entrada = $this->updateDB($id, $data);
            if ($entrada) {
                DB::commit();
                return response()->json($entrada, 201);
            } else {
                throw new Exception('Error al modificar el usuario', 1);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($th->getCode() == 23000) return response()->json([
                'errors' => ['email' => 'Email en uso']
            ], 422);
            dd($th);
            return response()->noContent(406);
        }
    }
    /**
     * Elimina una entrada
     */
    public function delete($id)
    {
        if ($this->deleteDB($id)) {
            return response()->noContent(200);
        } else {
            return response()->noContent(406);
        }
    }


    /** FUNCIONES PARA LA PERSISTENCIA DE DATOS */
    private function getDB($where = [], $take = false)
    {
        $users = [];
        // if (array_key_exists('activated', $where)) {
        //     $users = $users->where('activated', $where['activated']);
        // } else {
        //     $users = $users->where('activated', 1);
        // }
        // if (array_key_exists('blocked', $where)) {
        //     $users = $users->where('blocked', $where['blocked']);
        // } else {
        //     $users = $users->where('blocked', 0);
        // }
        if (array_key_exists('id', $where)) $users = \App\Models\User::where('id', $where['id']);
        else $users = \App\Models\User::where('id', '<>', 0);

        if ($take === false) {
            $users = $users->get();
        } elseif ($take == 1) {
            $users = $users->first();
        } else {
            $users = $users->take($take)->get();
        }
        return $users;
    }

    public function insertDB($data)
    {
        $nuevo = User::create($data);
        return $nuevo;
    }
    /**
     * Actualiza los datos de una entrada en la base de datos
     */
    public function updateDB($id, $data)
    {
        $update = User::where('id', $id)->update($data);
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
        return User::where('id', $id)->update([
            'activated' => 0
        ]) == 1;
    }
    /** FUNCIONES EXTRA */

    /**
     * Recupera los datos recibidos
     */
    public function getRequestData(Request $request)
    {
        $data = [];
        if (isset($request['name'])) $data['name'] = $request['name'];
        if (isset($request['lastName'])) $data['lastName'] = $request['lastName'];
        if (isset($request['email'])) $data['email'] = $request['email'];
        if (isset($request['password'])) $data['password'] = bcrypt($request['password']);

        return $data;
    }
}

<?php

use App\Http\Controllers\Aviso;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buscar;
use App\Http\Controllers\Libro;
use App\Http\Controllers\Cliente;
use App\Http\Controllers\Coche;
use App\Http\Controllers\Conductor;
use App\Http\Controllers\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// TODO: Crear middlewares
/**
 * Comprueba si el sistema está funcionando correctamente.
 */
Route::get('/test', function (Request $request) {
    try {
        // Test database connection
        DB::connection()->getPdo();
        return response()->noContent(200);
    } catch (\Exception $e) {
        return response()->noContent(400);
    }
});
/******* ACCESO *******/
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');;

Route::group([], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {

        /******* USUARIOS *******/
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/test', function (Request $params) {
            return response()->noContent(200);
        });
        // Recupera los usuarios
        Route::get('/users', [Users::class, 'getAll']);
        // Recuperar un usuario
        Route::get('/user/{id}', [Users::class, 'get']);
        // Nueva usuario
        Route::post('/user', [Users::class, 'insert']);
        // Actualiza un usuario
        Route::put('/user', [Users::class, 'update']);
        // Actualiza un usuario
        Route::put('/user/{id}', [Users::class, 'update']);
        // Elimina un usuario
        Route::delete('/user/{id}', [Users::class, 'delete']);

        /******* AGENDA *******/
        // Recupera los datos del aviso
        Route::get('/aviso', [Aviso::class, 'getAll']);
        // Recupera los datos de una entrada del aviso
        Route::get('/aviso/{id}', [Aviso::class, 'get']);
        // Busca las entradas del aviso en una fecha
        Route::get('/aviso/entradas/{fecha}', [Aviso::class, 'getByFecha']);
        // Busca las entradas de una semana
        Route::get('/aviso/semana/{fecha}', [Aviso::class, 'getSemana']);
        // Nueva entrada de aviso
        Route::post('/aviso', [Aviso::class, 'insert']);
        // Actualiza una entrada de aviso
        Route::put('/aviso/{id}', [Aviso::class, 'update']);
        // Elimina una entrada de aviso
        Route::delete('/aviso/{id}', [Aviso::class, 'delete']);
        // Confirma una entrada de aviso
        Route::put('/aviso/confirmar/{id}', [Aviso::class, 'confirm']);

        /******* COCHES *******/
        // Recupera los coches
        Route::get('/coche', [Coche::class, 'getAll']);
        // Recupera los datos de un coche
        Route::get('/coche/{id}', [Coche::class, 'get']);
        // Nueva entrada de coche
        Route::post('/coche', [Coche::class, 'insert']);
        // Actualiza una entrada de coche
        Route::put('/coche/{id}', [Coche::class, 'update']);
        // Elimina una entrada de coche
        Route::delete('/coche/{id}', [Coche::class, 'delete']);

        /******* CONDUCTORES *******/
        // Recupera los conductores
        Route::get('/conductor', [Conductor::class, 'getAll']);
        // Recupera los datos de un conductor
        Route::get('/conductor/{id}', [Conductor::class, 'get']);
        // Nueva entrada de conductor
        Route::post('/conductor', [Conductor::class, 'insert']);
        // Actualiza una entrada de conductor
        Route::put('/conductor/{id}', [Conductor::class, 'update']);
        // Elimina una entrada de conductor
        Route::delete('/conductor/{id}', [Conductor::class, 'delete']);

        /******* CLIENTES *******/
        // Recupera los clientes
        Route::get('/cliente', [Cliente::class, 'getAll']);
        // Recupera los datos de un cliente
        Route::get('/cliente/{id}', [Cliente::class, 'get']);
        // Nueva entrada de cliente
        Route::post('/cliente', [Cliente::class, 'insert']);
        // Actualiza una entrada de cliente
        Route::put('/cliente/{id}', [Cliente::class, 'update']);
        // Elimina una entrada de cliente
        Route::delete('/cliente/{id}', [Cliente::class, 'delete']);

        /******* LIBRO *******/
        // Recupera los datos de la libro
        Route::get('/libro', [Libro::class, 'getAll']);
        // Recupera los datos de una entrada de la libro
        Route::get('/libro/{id}', [Libro::class, 'get']);
        // Busca las entradas de la libro en una fecha
        Route::get('/libro/entradas/{fecha}', [Libro::class, 'getByFecha']);
        // Busca las entradas de una semana
        Route::get('/libro/semana/{fecha}', [Libro::class, 'getSemana']);
        // Nueva entrada de libro
        Route::post('/libro', [Libro::class, 'insert']);
        // Actualiza una entrada de libro
        Route::put('/libro/{id}', [Libro::class, 'update']);
        // Elimina una entrada de libro
        Route::delete('/libro/{id}', [Libro::class, 'delete']);

        /******* BUSCAR *******/
        Route::post('/buscar', [Buscar::class, 'get']);
    });
});

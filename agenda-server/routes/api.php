<?php

use App\Http\Controllers\Agenda;
use App\Http\Controllers\Libro;
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
Route::middleware('auth:api')->get('/login', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
/******* USUARIOS *******/
Route::get('/usuarios', [App\Http\Controllers\Usuarios::class, 'edit']);
Route::get('/usuario', [App\Http\Controllers\Usuarios::class, 'edit']);
/******* CONDUCTORES *******/
Route::get('/conductores', [App\Http\Controllers\Usuarios::class, 'edit']);
Route::get('/conductor', [App\Http\Controllers\Usuarios::class, 'edit']);
/******* COCHES *******/
Route::get('/coches', [App\Http\Controllers\Usuarios::class, 'edit']);
Route::get('/coche', [App\Http\Controllers\Usuarios::class, 'edit']);
/******* CLIENTES *******/
// Recupera los clientes
Route::get('/clientes', [App\Http\Controllers\Usuarios::class, 'edit']);
Route::get('/cliente', [App\Http\Controllers\Usuarios::class, 'edit']);

/******* AGENDA *******/
// Recupera los datos de la agenda
Route::get('/agenda', [Agenda::class, 'getAll']);
// Recupera los datos de una entrada de la agenda
Route::get('/agenda/{id}', [Agenda::class, 'get']);
// Busca las entradas de la agenda en una fecha
Route::get('/agenda/entradas/{fecha}', [Agenda::class, 'getByFecha']);
// Nueva entrada de agenda
Route::post('/agenda', [Agenda::class, 'insert']);
// Actualiza una entrada de agenda
Route::put('/agenda/{id}', [Agenda::class, 'update']);
// Elimina una entrada de agenda
Route::delete('/agenda/{id}', [Agenda::class, 'delete']);
// Confirma una entrada de agenda
Route::put('/agenda/confirmar/{id}', [Agenda::class, 'confirm']);

/******* LIBRO *******/
// Recupera los datos de la libro
Route::get('/libro', [Libro::class, 'getAll']);
// Recupera los datos de una entrada de la libro
Route::get('/libro/{id}', [Libro::class, 'get']);
// Busca las entradas de la libro en una fecha
Route::get('/libro/entradas/{fecha}', [Libro::class, 'getByFecha']);
// Nueva entrada de libro
Route::post('/libro', [Libro::class, 'insert']);
// Actualiza una entrada de libro
Route::put('/libro/{id}', [Libro::class, 'update']);
// Elimina una entrada de libro
Route::delete('/libro/{id}', [Libro::class, 'delete']);

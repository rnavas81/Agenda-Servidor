<?php

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
Route::get('/usuarios', function (Request $request) {
});
Route::get('/usuario', function (Request $request) {
});
/******* CONDUCTORES *******/
Route::get('/conductores', function (Request $request) {
});
Route::get('/conductor', function (Request $request) {
});
/******* COCHES *******/
Route::get('/coches', function (Request $request) {
});
Route::get('/coche', function (Request $request) {
});
/******* CLIENTES *******/
// Recupera los clientes
Route::get('/clientes', function (Request $request) {
});
Route::get('/cliente', function (Request $request) {
});
/******* AGENDA *******/
// Recupera los datos de la agenda
Route::get('/agenda', function (Request $request) {
});
// Recupera los datos de una entrada de la agenda
Route::get('/agenda/{id}', function (Request $request) {
});
// Busca las entradas de la agenda en una fecha
Route::get('/agenda/entradas/{fecha}', function (Request $request) {
});
// Nueva entrada de agenda
Route::post('/agenda', function (Request $request) {
});
// Actualiza una entrada de agenda
Route::put('/agenda/{id}', function (Request $request) {
});
// Confirma una entrada de agenda
Route::put('/agenda/confirmar/{id}', function (Request $request) {
});
// Elimina una entrada de agenda
Route::delete('/agenda/{id}', function (Request $request) {
});
/******* LIBRO *******/
Route::get('/libro', function (Request $request) {
});
// Recupera los datos de una entrada de la libro
Route::get('/libro/{id}', function (Request $request) {
});
// Busca las entradas de la libro en una fecha
Route::get('/libro/entradas/{fecha}', function (Request $request) {
});
// Nueva entrada de libro
Route::post('/libro', function (Request $request) {
});
// Actualiza una entrada de libro
Route::put('/libro/{id}', function (Request $request) {
});
// Elimina una entrada de libro
Route::delete('/libro/{id}', function (Request $request) {
});

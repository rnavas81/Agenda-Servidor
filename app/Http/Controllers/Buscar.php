<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\AvisoHistorico;
use App\Models\Cliente;
use App\Models\Libro;
use App\Models\LibroHistorico;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Buscar extends Controller
{
    /**
     * Recupera las entradas en función de los parametros enviados
     * 
     * Parametros a enviar:
     * @param Integer tipo 0->Ambos | 1->Avisos | 2->Viajes
     * @param Date desde 
     * @param Date hasta
     * @param String salida
     * @param String llegada
     * @param Integer cliente
     * @param Boolean cobrado
     * @param String factura
     */
    public function get(Request $request)
    {
        try {
            $request = $request->toArray();
            $data = [];
            $avisos = [];
            $viajes = [];
            $clientes = null;
            $clientesNombre = null;
            // Recupera los clientes
            if (!empty($request['cliente'])) {
                $clientes = [0];
                $request['cliente'] = strtolower($request['cliente']);
                $result = Cliente::whereRaw("LOWER (nombre) LIKE ?", ["%" . $request["cliente"] . "%"])->get();
                if ($result && count($result) > 0) {
                    foreach ($result as $cliente) {
                        $clientes[] = $cliente->id;
                    }
                }
            }
            //Si el tipo es 0 o 1 recupera los avisos
            if ($request['tipo'] == 0 || $request['tipo'] == 1) {
                if($request['origen']==0 || $request['origen']==2){
                    $avisos = Aviso::with('cliente')->where('habilitado', 1);
                    if ($clientes) $avisos = $avisos->whereIn('idCliente', $clientes);
                    if (!empty($request['desde'])) $avisos = $avisos->whereDate('salidaFecha', '>=', $request['desde']);
                    if (!empty($request['hasta'])) $avisos = $avisos->whereDate('salidaFecha', '<=', $request['hasta']);
                    if (!empty($request['salida'])) $avisos = $avisos->where('salidaLugar', 'LIKE', "%$request[salida]%");
                    if (!empty($request['llegada'])) $avisos = $avisos->where('llegadaLugar', 'LIKE', "%$request[llegada]%");
                    $avisos = $avisos->get()->toArray();
                    foreach ($avisos as $key => $record) {
                        $avisos[$key]['tipo'] = 1;
                        $avisos[$key]['idCliente']=$record['cliente']['nombre'];
                    }
                }
                if($request['origen']==0 || $request['origen']==1){
                    $avisos2 = AvisoHistorico::where('habilitado', 1);
                    if ($clientes) $avisos2 = $avisos2->whereRaw("LOWER (idCliente) LIKE ?", ["%" . $request["cliente"] . "%"]);
                    if (!empty($request['desde'])) $avisos2 = $avisos2->whereDate('salidaFecha', '>=', $request['desde']);
                    if (!empty($request['hasta'])) $avisos2 = $avisos2->whereDate('salidaFecha', '<=', $request['hasta']);
                    if (!empty($request['salida'])) $avisos2 = $avisos2->where('salidaLugar', 'LIKE', "%$request[salida]%");
                    if (!empty($request['llegada'])) $avisos2 = $avisos2->where('llegadaLugar', 'LIKE', "%$request[llegada]%");
                    $avisos2 = $avisos2->get()->toArray();
                    foreach ($avisos2 as $key => $record) {
                        $avisos2[$key]['tipo'] = 3;
                    }
                    $avisos = array_merge($avisos, $avisos2);
                }
            }
            //Si el tipo es 0 o 2 recupera los viajes
            if ($request['tipo'] == 0 || $request['tipo'] == 2) {
                $facturarA = null;
                if (!empty($request['facturarA'])) {
                    $facturarA = [0];
                    $request['facturarA'] = strtolower($request['facturarA']);
                    $result = Cliente::whereRaw("LOWER (nombre) LIKE ?", ["%" . $request["facturarA"] . "%"])->get();
                    if ($result && count($result) > 0) {
                        foreach ($result as $item) {
                            $facturarA[] = $item->id;
                        }
                    }
                }
                if($request['origen']==0 || $request['origen']==2){
                    $viajes = Libro::with('cliente')->where('habilitado', 1);
                    if ($clientes) $viajes = $viajes->whereIn('idCliente', $clientes);
                    if ($facturarA) $viajes = $viajes->whereIn('facturarA', $facturarA);
                    if (!empty($request['desde'])) $viajes = $viajes->whereDate('salidaFecha', '>=', $request['desde']);
                    if (!empty($request['hasta'])) $viajes = $viajes->whereDate('salidaFecha', '<=', $request['hasta']);
                    if (!empty($request['salida'])) $viajes = $viajes->where('salidaLugar', 'LIKE', "%$request[salida]%");
                    if (!empty($request['llegada'])) $viajes = $viajes->where('llegadaLugar', 'LIKE', "%$request[llegada]%");
                    if (array_key_exists('cobrado', $request) && $request['cobrado'] > -1) $viajes = $viajes->where('cobrado', $request['cobrado']);
                    if (!empty($request['facturaNumero'])) $viajes = $viajes->where('facturaNumero', 'LIKE', "%$request[facturaNumero]$");
                    $viajes = $viajes->get()->toArray();
                    foreach ($viajes as $key => $record) {
                        $viajes[$key]['tipo'] = 2;
                        $viajes[$key]['idCliente']=$record['cliente']['nombre'];
                    }
                }
                if($request['origen']==1 || $request['origen']==2){
                    $viajes2 = LibroHistorico::where('habilitado', 1);
                    if ($clientes) $viajes2 = $viajes2->whereRaw("LOWER (idCliente) LIKE ?", ["%" . $request["cliente"] . "%"]);
                    if ($facturarA) $viajes2 = $viajes2->whereRaw("LOWER (facturarA) LIKE ?", ["%" . $request["cliente"] . "%"]);
                    if (!empty($request['desde'])) $viajes2 = $viajes2->whereDate('salidaFecha', '>=', $request['desde']);
                    if (!empty($request['hasta'])) $viajes2 = $viajes2->whereDate('salidaFecha', '<=', $request['hasta']);
                    if (!empty($request['salida'])) $viajes2 = $viajes2->where('salidaLugar', 'LIKE', "%$request[salida]%");
                    if (!empty($request['llegada'])) $viajes2 = $viajes2->where('llegadaLugar', 'LIKE', "%$request[llegada]%");
                    if (array_key_exists('cobrado', $request) && $request['cobrado'] > -1) $viajes2 = $viajes2->where('cobrado', $request['cobrado']);
                    if (!empty($request['facturaNumero'])) $viajes2 = $viajes2->where('facturaNumero', 'LIKE', "%$request[facturaNumero]$");
                    $viajes2 = $viajes2->get()->toArray();
                    foreach ($viajes2 as $key => $record) {
                        $viajes2[$key]['tipo'] = 4;
                    }
                    $viajes = array_merge($viajes, $viajes2);
                }
            }
            switch ($request['tipo']) {
                case 0:
                    // $data = $avisos->mergeRecursive($viajes)->sortBy('salidaFecha');
                    $data = array_merge($avisos, $viajes);
                    usort($data, function ($a, $b) {
                        return strcmp($a["salidaFecha"], $b["salidaFecha"]);
                    });

                    break;
                case 1:
                    $data = $avisos;
                    break;
                case 2:
                    $data = $viajes;
                    break;
                default:
                    # code...
                    break;
            }
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->noContent(406);
        }
    }
    function cmp($a, $b)
    {
        return strcmp($a->name, $b->name);
    }
}

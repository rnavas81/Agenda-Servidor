<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\Libro;
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
            //Si el tipo es 0 o 1 recupera los avisos
            if ($request['tipo'] == 0 || $request['tipo'] == 1) {
                $avisos = Aviso::with('cliente')->where('habilitado', 1);
                if (!empty($request['desde'])) $avisos = $avisos->whereDate('salidaFecha', '>=', $request['desde']);
                if (!empty($request['hasta'])) $avisos = $avisos->whereDate('salidaFecha', '<=', $request['hasta']);
                if (!empty($request['salida'])) $avisos = $avisos->where('salidaLugar', 'LIKE', "%$request[salida]%");
                if (!empty($request['llegada'])) $avisos = $avisos->where('llegadaLugar', 'LIKE', "%$request[llegada]%");
                if ($request['cliente'] > 0) $avisos = $avisos->where('idCliente', $request['cliente']);
                $avisos = $avisos->get()->toArray();
                foreach ($avisos as $key => $record) {
                    $avisos[$key]['tipo'] = 1;
                }
            }
            //Si el tipo es 0 0 2 recupera los viajes
            if ($request['tipo'] == 0 || $request['tipo'] == 2) {
                $viajes = Libro::with('cliente')->where('habilitado', 1);
                if (!empty($request['desde'])) $viajes = $viajes->whereDate('salidaFecha', '>=', $request['desde']);
                if (!empty($request['hasta'])) $viajes = $viajes->whereDate('salidaFecha', '<=', $request['hasta']);
                if (!empty($request['salida'])) $viajes = $viajes->where('salidaLugar', 'LIKE', "%$request[salida]%");
                if (!empty($request['llegada'])) $viajes = $viajes->where('llegadaLugar', 'LIKE', "%$request[llegada]%");
                if (array_key_exists('cobrado', $request) && $request['cobrado'] > -1) $viajes = $viajes->where('cobrado', $request['cobrado']);
                if (!empty($request['facturaNumero'])) $viajes = $viajes->where('facturaNumero', 'LIKE', "%$request[facturaNumero]$");
                if (!empty($request['facturarA'])) $viajes = $viajes->where('facturarA', 'LIKE', "%$request[facturarA]%");

                if ($request['cliente'] > 0) $viajes = $viajes->where('idCliente', $request['cliente']);
                $viajes = $viajes->get()->toArray();
                foreach ($viajes as $key => $record) {
                    $viajes[$key]['tipo'] = 2;
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

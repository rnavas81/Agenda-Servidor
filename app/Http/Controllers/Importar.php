<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Coche;
use App\Models\Conductor;
use App\Models\Libro;
use App\Models\LibroCoches;
use App\Models\LibroConductores;
use App\Models\LibroHistorico;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Importar extends Controller
{
    /**
     * Importar datos de un fichero excell
     * @param tipo 
     *      1=> datos para agenda
     *      2=> datos para libro
     * @param donde
     *      1=> guardar en historico
     *      2=> guardar en actual
     */
    public function set(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            $data = $this->getRequestData($request);
            try {
                $tmp_info = pathinfo($_FILES["thumbnail"]["tmp_name"]);
                $directory = $tmp_info['dirname'] . "/";
                $file_info = pathinfo($_FILES["thumbnail"]["name"]);
                $new_file = date("dmY") . "." . $file_info["extension"];
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $directory . $new_file);
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($directory . $new_file);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($directory . $new_file);
                /**  Convert Spreadsheet Object to an Array for ease of use  **/

                $schdeules = $spreadsheet->getActiveSheet();
                $cols = [];
                $idViajeCol = null;
                DB::beginTransaction();
                foreach ($schdeules->getRowIterator() as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    if ($key == 1) {
                        $keys = [];
                        foreach ($cellIterator as $key => $cell) {
                            $cols[$key] = $cell->getValue();
                            if ($cell->getValue() == "IdViaje") $idViajeCol = $key;
                        }
                    } else {
                        if ($data["tipo"] == 1) { //Datos para agenda
                            if ($data["donde"] == 1) { //Datos para historico
                                $this->guardarAgendaHistorico($cellIterator, $cols, $idViajeCol);
                            } elseif ($data["donde"] == 2) { //Datos en actual
                                $this->guardarAgendaActual($cellIterator, $cols, $idViajeCol);
                            }
                        } else if ($data["tipo"] == 2) {
                            if ($data["donde"] == 1) { //Datos para historico
                                $this->guardarLibroHistorico($cellIterator, $cols, $idViajeCol);
                            } elseif ($data["donde"] == 2) { //Datos en actual
                                $this->guardarLibroActual($cellIterator, $cols, $idViajeCol);
                            }
                        }
                    }
                }

                // /**  Convert Spreadsheet Object to an Array for ease of use  **/
                // $schdeules = $spreadsheet->getActiveSheet()->toArray();
                // // Recupera las columnas de la primera fila
                // $cols = array_values($schdeules[0]);
                // DB::beginTransaction();
                // for ($i = 1; $i < count($schdeules); $i++) {
                //     $row = $schdeules[$i];
                //     $row = $schdeules[$i];
                //     if ($data["tipo"] == 1) { //Datos para agenda
                //         if ($data["donde"] == 1) { //Datos para historico
                //             $this->guardarAgendaHistorico($row, $cols);
                //         } elseif ($data["donde"] == 2) { //Datos en actual
                //             $this->guardarAgendaActual($row, $cols);
                //         }
                //     } else if ($data["tipo"] == 2) {
                //         if ($data["donde"] == 1) { //Datos para historico
                //             $this->guardarLibroHistorico($row, $cols);
                //         } elseif ($data["donde"] == 2) { //Datos en actual
                //             $this->guardarLibroActual($row, $cols);
                //         }
                //     }
                // }
                DB::commit();
                return response("Importación correcta", 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response($th->getMessage(), 406);
            } finally {
                if (is_dir($directory . $new_file)) unlink($directory . $new_file);
            }
        }
    }

    public function guardarAgendaHistorico($row, $cols)
    {
        dd("guardarAgendaHistorico");
    }
    public function guardarAgendaActual($row, $cols)
    {
        // Comprueba si existe en la tabla
        $entrada = Libro::where('idMigracion', $row['IdViaje'])->first();
        if (!!$entrada) return;
        $insert = [
            "idUsuario" => 0
        ];
    }
    public function guardarLibroHistorico($cellIterator, $cols, $idViajeCol)
    {
        $insert = [
            "idUsuario" => '0',
            "habilitado" => '1',
        ];

        foreach ($cellIterator as $key => $cell) {
            if ($key == $idViajeCol) { // Comprueba si existe en la tabla
                $entrada = LibroHistorico::where('idMigracion', $cell->getValue())->first();
                if ($entrada != null) {
                    $insert = null;
                    break;
                }
            }
            $col = $cols[$key];
            $value = $cell->getValue();
            if (empty($value)) continue;
            switch ($col) {
                case 'Conductor':
                    $insert['conductores'] = strval($value);
                    break;
                case 'Matricula':
                    $insert['coches'] = strval($value);
                    break;
                case 'Cliente':
                    $insert['idCliente'] = strval($value);
                    break;
                case 'FechaSalida':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['salidaFecha'] = $fecha->format('Y-m-d');
                    break;
                case 'Hora Salida':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['salidaHora'] = $fecha->format('H:i:s');
                    break;
                case 'FechaLlegada':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['llegadaFecha'] = $fecha->format('Y-m-d');
                    break;
                case 'Hora Llegada':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['llegadaHora'] = $fecha->format('H:i:s');
                    break;
                case 'Fecha pago':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['cobradoFecha'] = $fecha->format('Y-m-d');
                    break;
                case 'Factura a':
                    $insert['facturarA'] = strval($value);
                    break;
                case 'Itinerario':
                    $insert['itinerario'] = strval($value);
                    if (str_contains($value, "-")) {
                        $vals = explode('-', $value);
                        $insert['salidaLugar'] = $vals[0];
                        $insert['llegadaLugar'] = end($vals);
                    } else  $insert['llegadaLugar'] = $value;
                    break;
                case 'Kms':
                    $insert['kms'] = strval($value);
                    break;
                case 'Nombre Contacto':
                    $insert['contacto'] = strval($value);
                    break;
                case 'Teléfono':
                    $insert['contactoTlf'] = strval($value);
                    break;
                case 'Importe':
                    $value = str_replace('.', '', $value);
                    $insert['importe'] = str_replace(',', '.', $value);
                    break;
                case 'Pagado':
                    $insert['cobrado'] = strval($value);
                    break;
                case 'Forma pago':
                    $insert['cobradoForma'] = strval($value);
                    break;
                case 'Gastos':
                    $insert['gastos'] = strval($value);
                    break;
                case 'Factura':
                    $insert['facturaNombre'] = strval($value);
                    break;
                case 'Banco':
                    $insert['cobradoDetalle'] = strval($value);
                    break;
                case 'Nº FACTURA':
                    $insert['facturaNumero'] = strval($value);
                    break;
                case 'IdViaje':
                    $insert['idMigracion'] = strval($value);
                    break;
                    // case 'Albarán': $insert['itinerario'] = strval($value); break;
                    // case 'Nº': $insert['itinerario'] = strval($value); break;
                default:
                    break;
            }
        }
        // Crea la entrada del libro
        if ($insert) $libro = LibroHistorico::create($insert);
    }
    public function guardarLibroActual($cellIterator, $cols, $idViajeCol)
    {
        $insert = [
            "idUsuario" => 0
        ];
        $conductores = null;
        $coche = null;
        foreach ($cellIterator as $key => $cell) {
            if ($key == $idViajeCol) { // Comprueba si existe en la tabla
                $entrada = Libro::where('idMigracion', $cell->getValue())->first();
                if ($entrada != null) {
                    $insert = null;
                    break;
                }
            }
            $col = $cols[$key];
            $value = $cell->getValue();
            if (empty($value)) continue;

            switch ($col) {
                case 'Conductor':
                    $conductores = $this->testConductor($value);
                    break;
                case 'Matricula':
                    $coche = $this->testCoche($value);
                    break;
                case 'Cliente':
                    $cliente = $this->testCliente($value);
                    $insert['idCliente'] = $cliente->id;
                    break;
                case 'FechaSalida':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['salidaFecha'] = $fecha->format('Y-m-d');
                    break;
                case 'Hora Salida':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['salidaHora'] = $fecha->format('H:i:s');
                    break;
                case 'FechaLlegada':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['llegadaFecha'] = $fecha->format('Y-m-d');
                    break;
                case 'Hora Llegada':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['llegadaHora'] = $fecha->format('H:i:s');
                    break;
                case 'Fecha pago':
                    $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    $insert['cobradoFecha'] = $fecha->format('Y-m-d');
                    break;
                case 'Factura a':
                    $cliente = $this->testCliente($value);
                    $insert['facturarA'] = $cliente->id;
                    break;

                case 'Itinerario':
                    $insert['itinerario'] = $value;
                    if (str_contains($value, "-")) {
                        $vals = explode('-', $value);
                        $insert['salidaLugar'] = $vals[0];
                        $insert['llegadaLugar'] = end($vals);
                    } else $insert['llegadaLugar'] = $value;
                    break;
                case 'Kms':
                    $insert['kms'] = $value;
                    break;
                case 'Nombre Contacto':
                    $insert['contacto'] = $value;
                    break;
                case 'Teléfono':
                    $insert['contactoTlf'] = $value;
                    break;
                    // case 'Albarán': $insert['itinerario'] = $value; break;
                case 'Importe':
                    $value = str_replace('.', '', $value);
                    $insert['importe'] = floatval(str_replace(',', '.', $value));
                    break;
                case 'Pagado':
                    $insert['cobrado'] = $value;
                    break;
                case 'Forma pago':
                    $insert['cobradoForma'] = $value;
                    break;
                    // case 'Nº': $insert['itinerario'] = $value; break;
                case 'Gastos':
                    $insert['gastos'] = $value;
                    break;
                case 'Factura':
                    $insert['facturaNombre'] = $value;
                    break;
                case 'Banco':
                    $insert['cobradoDetalle'] = $value;
                    break;
                case 'Nº FACTURA':
                    $insert['facturaNumero'] = $value;
                    break;
                case 'IdViaje':
                    $insert['idMigracion'] = strval($value);
                    break;
                default:
                    break;
            }
        }
        // Crea la entrada del libro
        if ($insert) {
            $libro = Libro::create($insert);
            // Crea la relación con conductor
            if ($conductores) {
                foreach ($conductores as $conductor) {
                    LibroConductores::create([
                        'idLibro' => $libro->id, 'idConductor' => $conductor->id
                    ]);
                }
            }
            // Crea la relación con coche            
            if ($coche) {
                LibroCoches::create([
                    'idLibro' => $libro->id, 'idCoche' => $coche->id
                ]);
            }
        }
    }
    /**
     * Comprueba si existe el conductor o lo crea
     */
    public function testConductor($value)
    {
        $values = [];
        if (str_contains($value, "-")) {
            $vals = explode('-', $value);
            foreach ($vals as $val) {
                $find = Conductor::whereRaw("upper(nombre) = '" . strtoupper(trim($val)) . "'")->first();
                if (!$find) {
                    $find = Conductor::create(['nombre' => strtoupper(trim($val))]);
                }
                $values[] = $find;
            }
        } else {
            $find = Conductor::whereRaw("upper(nombre) = '" . strtoupper(trim($value)) . "'")->first();
            if (!$find) {
                $find = Conductor::create(['nombre' => strtoupper(trim($value))]);
            }
            $values[] = $find;
        }
        return $values;
    }
    /**
     * Comprueba si existe el conductor o lo crea
     */
    public function testCoche($value)
    {
        $find = Coche::whereRaw("upper(matricula) = '" . strtoupper($value) . "'")->first();
        if (!$find) {
            $find = Coche::create(['matricula' => strtoupper($value)]);
        }
        return $find;
    }
    /**
     * Comprueba si existe el conductor o lo crea
     */
    public function testCliente($value)
    {
        $find = Cliente::whereRaw("upper(nombre) = '" . strtoupper($value) . "'")->first();
        if (!$find) {
            $find = Cliente::create(['nombre' => strtoupper($value)]);
        }
        return $find;
    }
    /** FUNCIONES EXTRA */

    /**
     * Recupera los datos recibidos
     */
    public function getRequestData(Request $request)
    {
        $request->validate([
            'tipo'     => ['required', 'integer'],
            'donde' => ['required', 'integer'],
        ]);
        $data = [
            'tipo' => $request['tipo'],
            'donde' => $request['donde'],
        ];

        return $data;
    }
}

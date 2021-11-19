<?php

namespace Database\Seeders;

use App\Http\Controllers\Aviso;
use DateInterval;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') === 'local') {

            $fak = \Faker\Factory::create('es_ES');
            $fak2 = \Faker\Factory::create('ms_MY');
            $usuarios = [
                [
                    'name' => 'admin',
                    'username' => 'admin',
                    'password' => bcrypt("admin"),
                    'email' => $fak->email,
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Julián',
                    'username' => 'jRivilla',
                    'password' => bcrypt("jRivilla"),
                    'email' => 'autobusesrivilla@hotmail.com',
                    'email_verified_at' => now(),
                ], [
                    'name' => 'Felipe',
                    'username' => 'fNavas',
                    'password' => bcrypt("fNavas"),
                    'email' => 'fnavasar@hotmail.com',
                    'email_verified_at' => now(),
                ],
            ];
            for ($i = 0; $i < 5; $i++) {
                $usuarios[] = [
                    'name' => $fak->firstName,
                    'lastName' => $fak->lastName,
                    'username' => $fak->userName,
                    'password' => bcrypt(123),
                    'email' => $fak->email,
                    'email_verified_at' => now(),
                ];
            }
            foreach ($usuarios as $usuario) {
                \App\Models\User::create($usuario);
            }
            // Crea clientes ficticios
            $clientes = [];
            for ($i = 0; $i < 10; $i++) {
                $cliente = [
                    'nombre' => $fak->company,
                    'telefono' => $fak->phoneNumber,
                ];
                \App\Models\Cliente::create($cliente);
                $clientes[] = $cliente;
            }
            // Crea coches ficticios
            $coches = [];
            for ($i = 0; $i < 10; $i++) {
                $coche = [
                    'matricula' => $fak2->jpjNumberPlate,
                ];
                \App\Models\Coche::create($coche);
                $coches[] = $coche;
            }
            // Crea conductores ficticios
            $conductores = [];
            for ($i = 0; $i < 10; $i++) {
                $conductor = [
                    'nombre' => $fak->firstName . " " . $fak->lastName,
                ];
                \App\Models\Conductor::create($conductor);
                $conductores[] = $conductor;
            }
            // Coches para avisos
            $coches_avisos = Config::get('app.coches_avisos');
            $respuestas = Config::get('app.respuestas_avisos');
            $inicio = new DateTime('2020-1-1');
            $fecha = new DateTime('2021-1-1');
            $fin = new DateTime();
            $fin->add(new DateInterval('P1M'));
            // Crea entradas de aviso y viajes para historico
            while ($inicio < $fecha) {
                $max = rand(0, 3);
                for ($i = 0; $i <  $max; $i++) {
                    $init = rand(0, 23);
                    $fecha->setTime($init, 0, 0);
                    $usuario = $usuarios[rand(0, count($usuarios) - 1)];
                    $cliente = $clientes[rand(0, count($clientes) - 1)];
                    $llegada = new DateTime($fecha->format("Y-m-d H:i:s"));
                    $d = rand(1, 4);
                    $llegada->add(new DateInterval('P' . $d . 'D'));
                    $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
                    $salida = $fak->city();
                    $destino = $fak->city();
                    $coches_guardar = [];
                    $presupuesto = rand(1, 5) * 100;
                    $e = rand(0, 3);
                    for ($j = 0; $j < $e; $j++) {
                        $coches_guardar[] = $coches_avisos[rand(0, count($coches_avisos) - 1)] . '-' . $presupuesto;
                    }
                    // Entradas de la aviso
                    $aviso = \App\Models\AvisoHistorico::create([
                        'habilitado' => 1,
                        'idUsuario' => $usuario['name'],
                        'salidaFecha' => $fecha->format('Y-m-d'),
                        'salidaHora' => $fecha->format('H:i:s'),
                        'salidaLugar' => $salida,
                        'llegadaFecha' => $llegada->format('Y-m-d'),
                        'llegadaHora' => $llegada->format('H:i:s'),
                        'llegadaLugar' => $destino,
                        'idCliente' => $cliente['nombre'],
                        'clienteDetalle' => $clienteDetalle,
                        'respuesta' => $respuestas[rand(0, count($respuestas) - 1)],
                        'respuestaFecha' => $fecha->format('Y-m-d'),
                        'respuestaDetalle' => $fak->sentence($nbWords = 6, $variableNbWords = true),
                        'coches' => implode('##', $coches_guardar),
                        'confirmada' => 1
                    ]);
                    foreach ($coches_guardar as $coche) {
                        // Entradas del libro
                        $libro = \App\Models\LibroHistorico::create([
                            'habilitado' => 1,
                            'idAviso' => $aviso->id,
                            'idUsuario' => $usuario['name'],
                            'salidaFecha' => $fecha->format('Y-m-d'),
                            'salidaHora' => $fecha->format('H:i:s'),
                            'salidaLugar' => $salida,
                            'llegadaFecha' => $llegada->format('Y-m-d'),
                            'llegadaHora' => $llegada->format('H:i:s'),
                            'llegadaLugar' => $destino,
                            'idCliente' => $cliente['nombre'],
                            'clienteDetalle' => $clienteDetalle,
                            'importe' => $presupuesto,
                            'facturarA' => $clientes[rand(0, count($clientes) - 1)]['nombre'],
                            'observaciones' => $coche,
                            'coches' => $coches[rand(0, count($coches) - 1)]['matricula'],
                            'conductores' => $conductores[rand(0, count($conductores) - 1)]['nombre'],
                        ]);
                    }
                }
                for ($i = 0; $i <  $max; $i++) {
                    $init = rand(0, 23);
                    $fecha->setTime($init, 0, 0);
                    $usuario = $usuarios[rand(0, 7)];
                    $cliente = $clientes[rand(0, count($clientes) - 1)];
                    $llegada = new DateTime($fecha->format("Y-m-d H:i:s"));
                    $llegada->add(new DateInterval('P' . rand(1, 4) . 'D'));
                    $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
                    $salida = $fak->city();
                    $destino = $fak->city();
                    $coches_guardar = [];
                    $presupuesto = rand(1, 5) * 100;
                    $e = rand(0, 3);
                    for ($j = 0; $j < $e; $j++) {
                        $coches_guardar[] = $coches_avisos[rand(0, count($coches_avisos) - 1)] .'-'. $presupuesto;
                    }
                    // Entradas de la aviso
                    $aviso = \App\Models\AvisoHistorico::create([
                        'habilitado' => 1,
                        'idUsuario' => $usuario['name'],
                        'salidaFecha' => $fecha->format('Y-m-d'),
                        'salidaHora' => $fecha->format('H:i:s'),
                        'salidaLugar' => $salida,
                        'llegadaFecha' => $llegada->format('Y-m-d'),
                        'llegadaHora' => $llegada->format('H:i:s'),
                        'llegadaLugar' => $destino,
                        'idCliente' => $cliente['nombre'],
                        'clienteDetalle' => $clienteDetalle,
                        'respuesta' => $respuestas[rand(0, count($respuestas) - 1)],
                        'respuestaFecha' => $fecha->format('Y-m-d'),
                        'respuestaDetalle' => $fak->sentence($nbWords = 6, $variableNbWords = true),
                        'coches' => implode('##', $coches_guardar),
                        'confirmada' => 1
                    ]);
                    // Entrada para libro
                    $libro = \App\Models\LibroHistorico::create([
                        'habilitado' => 1,
                        'idUsuario' => $usuario['name'],
                        'salidaFecha' => $fecha->format('Y-m-d'),
                        'salidaHora' => $fecha->format('H:i:s'),
                        'salidaLugar' => $salida,
                        'llegadaFecha' => $llegada->format('Y-m-d'),
                        'llegadaHora' => $llegada->format('H:i:s'),
                        'llegadaLugar' => $destino,
                        'idCliente' => $cliente['nombre'],
                        'clienteDetalle' => $clienteDetalle,
                        'importe' => $presupuesto,
                        'facturarA' => $clientes[rand(0, count($clientes) - 1)]['nombre'],
                        'observaciones' => $coche,
                        'coches' => $coches[rand(0, count($coches) - 1)]['matricula'],
                        'conductores' => $conductores[rand(0, count($conductores) - 1)]['nombre'],
                    ]);
                }
                $avance = rand(1, 3);
                $inicio->add(new DateInterval('P' . $avance . 'D'));
            }

            // Crea entradas para aviso confirmadas y libro
            while ($fecha < $fin) {
                $max = rand(0, 3);
                for ($i = 0; $i <  $max; $i++) {
                    $init = rand(0, 23);
                    $fecha->setTime($init, 0, 0);
                    $usuario = rand(1, 6);
                    $cliente = rand(1, 10);
                    $conductor = rand(1, 10);
                    $llegada = new DateTime($fecha->format("Y-m-d H:i:s"));
                    $d = rand(1, 4);
                    $llegada->add(new DateInterval('P' . $d . 'D'));
                    $sitio = $fak->city;
                    $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
                    // Entradas de la aviso
                    $aviso = \App\Models\Aviso::create([
                        'idUsuario' => $usuario,
                        'salidaFecha' => $fecha->format('Y-m-d'),
                        'salidaHora' => $fecha->format('H:i:s'),
                        'salidaLugar' => $sitio,
                        'llegadaFecha' => $llegada->format('Y-m-d'),
                        'llegadaHora' => $llegada->format('H:i:s'),
                        'llegadaLugar' => $sitio,
                        'idCliente' => $cliente,
                        'clienteDetalle' => $clienteDetalle,
                        'respuesta' => rand(0, count($respuestas) - 1),
                        'respuestaFecha' => $fecha->format('Y-m-d'),
                        'respuestaDetalle' => $fak->sentence($nbWords = 6, $variableNbWords = true),
                        'confirmada' => 1
                    ]);
                    for ($j = 0; $j < 3; $j++) {
                        $presupuesto = rand(1, 5) * 100;
                        \App\Models\AvisoCoches::create([
                            'idAviso' => $aviso->id,
                            'idCoche' => rand(1, 10),
                            'presupuesto' => $presupuesto
                        ]);
                        // Entradas del libro
                        $libro = \App\Models\Libro::create([
                            'idAviso' => $aviso->id,
                            'idUsuario' => $usuario,
                            'salidaFecha' => $fecha->format('Y-m-d'),
                            'salidaHora' => $fecha->format('H:i:s'),
                            'salidaLugar' => $sitio,
                            'llegadaFecha' => $llegada->format('Y-m-d'),
                            'llegadaHora' => $llegada->format('H:i:s'),
                            'llegadaLugar' => $sitio,
                            'idCliente' => $cliente,
                            'clienteDetalle' => $clienteDetalle,
                            'importe' => $presupuesto,
                            'facturarA' => rand(1, 10),
                        ]);

                        \App\Models\LibroCoches::create([
                            'idLibro' => $libro->id,
                            'idCoche' => rand(1, 10)
                        ]);
                        \App\Models\LibroConductores::create([
                            'idLibro' => $libro->id,
                            'idConductor' => rand(1, 10)
                        ]);
                    }
                }
                for ($i = 0; $i <  $max; $i++) {
                    $init = rand(0, 23);
                    $fecha->setTime($init, 0, 0);
                    $usuario = rand(1, 6);
                    $cliente = rand(1, 10);
                    $llegada = new DateTime($fecha->format("Y-m-d H:i:s"));
                    $d = rand(1, 4);
                    $llegada->add(new DateInterval('P' . $d . 'D'));
                    $llegada->setTime($init, 0, 0);
                    $sitio = $fak->city;
                    $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
                    $respuesta = rand(0, count($respuestas) - 1);
                    $entrada = \App\Models\Aviso::create([
                        'idUsuario' => $usuario,
                        'salidaFecha' => $fecha->format('Y-m-d'),
                        'salidaHora' => $fecha->format('H:i:s'),
                        'salidaLugar' => $sitio,
                        'llegadaFecha' => $llegada->format('Y-m-d'),
                        'llegadaHora' => $llegada->format('H:i:s'),
                        'llegadaLugar' => $sitio,
                        'idCliente' => $cliente,
                        'clienteDetalle' => $fak->sentence($nbWords = 6, $variableNbWords = true),
                        'respuesta' => $respuesta,
                        'respuestaFecha' => $respuesta != 0 ? $fecha->format('Y-m-d') : null,
                        'respuestaDetalle' => $fak->sentence($nbWords = 6, $variableNbWords = true),
                    ]);
                    for ($j = 0; $j < 3; $j++) {
                        \App\Models\AvisoCoches::create([
                            'idAviso' => $entrada->id,
                            'idCoche' => rand(1, 10),
                            'presupuesto' => rand(1, 5) * 100
                        ]);
                    }
                }
                $fecha->add(new DateInterval('P1D'));
            }
        }
    }
}

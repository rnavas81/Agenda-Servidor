<?php

namespace Database\Seeders;

use App\Http\Controllers\Aviso;
use DateInterval;
use DateTime;
use Illuminate\Database\Seeder;

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
            \App\Models\User::create([
                'name' => 'Julián',
                'username' => 'jRivilla',
                'password' => bcrypt("jRivilla"),
                'email' => 'autobusesrivilla@hotmail.com',
                'email_verified_at' => now(),
            ]);
            \App\Models\User::create([
                'name' => 'Felipe',
                'username' => 'fNavas',
                'password' => bcrypt("fNavas"),
                'email' => 'fnavasar@hotmail.com',
                'email_verified_at' => now(),
            ]);
            \App\Models\User::create([
                'name' => 'admin',
                'username' => 'admin',
                'password' => bcrypt("admin"),
                'email' => $fak->email,
                'email_verified_at' => now(),
            ]);
            for ($i = 0; $i < 5; $i++) {
                \App\Models\User::create([
                    'name' => $fak->firstName,
                    'lastName' => $fak->lastName,
                    'username' => $fak->userName,
                    'password' => bcrypt(123),
                    'email' => $fak->email,
                    'email_verified_at' => now(),
                ]);
            }
            // Crea clientes ficticios
            for ($i = 0; $i < 10; $i++) {
                \App\Models\Cliente::create([
                    'nombre' => $fak->company,
                    'telefono' => $fak->phoneNumber,
                ]);
            }
            // Crea coches ficticios

            for ($i = 0; $i < 10; $i++) {
                \App\Models\Coche::create([
                    'matricula' => $fak2->jpjNumberPlate,
                ]);
            }
            // Crea conductores ficticios
            for ($i = 0; $i < 10; $i++) {
                \App\Models\Conductor::create([
                    'nombre' => $fak->firstName . " " . $fak->lastName,
                ]);
            }
            // Crea entradas para aviso confirmadas y libro
            $fecha = new DateTime();
            $fecha->sub(new DateInterval('P1M'));
            $fin = new DateTime();
            $fin->add(new DateInterval('P2M'));
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
                        'respuesta' => rand(1, 4),
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
                    $respuesta = rand(0, 4);
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

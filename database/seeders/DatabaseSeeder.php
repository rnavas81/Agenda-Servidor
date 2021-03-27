<?php

namespace Database\Seeders;

use DateInterval;
use DateTime;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $fak = \Faker\Factory::create('es_ES');
        $fak2 = \Faker\Factory::create('ms_MY');
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
        // Crea entradas para agenda confirmadas y libro
        $fecha = new DateTime();
        $fecha->sub(new DateInterval('P1M'));
        $fin = new DateTime();
        $fin->add(new DateInterval('P2M'));
        while ($fecha < $fin) {
            $max = rand(5, 20);
            for ($i = 0; $i <  $max; $i++) {
                $init = rand(0, 23);
                $fecha->setTime($init, 0, 0);

                $usuario = rand(1, 6);
                $cliente = rand(1, 10);
                $coche = rand(1, 10);
                $conductor = rand(1, 10);
                $llegada = new DateTime($fecha->format("Y-m-d H:i:s"));
                $d = rand(1, 4);
                $llegada->add(new DateInterval('P' . $d . 'D'));
                $sitio = $fak->city;
                $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
                // Entradas de la agenda
                $entrada = \App\Models\AgendaEntrada::create([
                    'idUsuario' => $usuario,
                    'salidaFecha' => $fecha->format('Y-m-d'),
                    'salidaHora' => $fecha->format('H:i:s'),
                    'salidaLugar' => $sitio,
                    'llegadaFecha' => $llegada->format('Y-m-d'),
                    'llegadaHora' => $llegada->format('H:i:s'),
                    'llegadaLugar' => $sitio,
                    'idCliente' => $cliente,
                    'clienteDetalle' => $clienteDetalle,
                    'confirmada' => 1
                ]);
                \App\Models\AgendaCoches::create([
                    'idAgenda' => $entrada->id,
                    'idCoche' => $coche
                ]);
                \App\Models\AgendaConductores::create([
                    'idAgenda' => $entrada->id,
                    'idConductor' => $conductor
                ]);
                // Entradas del libro
                $entrada = \App\Models\LibroEntrada::create([
                    'idAgenda' => $entrada->id,
                    'idUsuario' => $usuario,
                    'salidaFecha' => $fecha->format('Y-m-d'),
                    'salidaHora' => $fecha->format('H:i:s'),
                    'salidaLugar' => $sitio,
                    'llegadaFecha' => $llegada->format('Y-m-d'),
                    'llegadaHora' => $llegada->format('H:i:s'),
                    'llegadaLugar' => $sitio,
                    'idCliente' => $cliente,
                    'clienteDetalle' => $clienteDetalle,
                ]);
                \App\Models\LibroCoches::create([
                    'idLibro' => $entrada->id,
                    'idCoche' => $coche
                ]);
                \App\Models\LibroConductores::create([
                    'idLibro' => $entrada->id,
                    'idConductor' => $conductor
                ]);
            }
            $fecha->add(new DateInterval('P1D'));
        }
        $fecha->sub(new DateInterval('P1M'));
        while ($fecha < $fin) {
            $max = rand(3, 5);
            for ($i = 0; $i <  $max; $i++) {
                $init = rand(0, 23);
                $fecha->setTime($init, 0, 0);
                $usuario = rand(1, 6);
                $cliente = rand(1, 10);
                $coche = rand(1, 10);
                $conductor = rand(1, 10);
                $llegada = new DateTime($fecha->format("Y-m-d H:i:s"));
                $d = rand(1, 4);
                $llegada->add(new DateInterval('P' . $d . 'D'));
                $init = rand(0, 23);
                $llegada->setTime($init, 0, 0);
                $sitio = $fak->city;
                $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
                $entrada = \App\Models\AgendaEntrada::create([
                    'idUsuario' => $usuario,
                    'salidaFecha' => $fecha->format('Y-m-d'),
                    'salidaHora' => $fecha->format('H:i:s'),
                    'salidaLugar' => $sitio,
                    'llegadaFecha' => $llegada->format('Y-m-d'),
                    'llegadaHora' => $llegada->format('H:i:s'),
                    'llegadaLugar' => $sitio,
                    'idCliente' => $cliente,
                    'clienteDetalle' => $fak->sentence($nbWords = 6, $variableNbWords = true),

                ]);
                \App\Models\AgendaCoches::create([
                    'idAgenda' => $entrada->id,
                    'idCoche' => $coche
                ]);
                \App\Models\AgendaConductores::create([
                    'idAgenda' => $entrada->id,
                    'idConductor' => $conductor
                ]);
            }
            $fecha->add(new DateInterval('P1D'));
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

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
            'name'=>'admin',
            'username'=>'admin',
            'password' => bcrypt("admin"),
            'email' => $fak->email,
            'email_verified_at' => now(),
        ]);
        \App\Models\User::create([
            'name'=>$fak->firstName,
            'lastName'=>$fak->lastName,
            'username'=>$fak->userName,
            'password' => bcrypt(123),
            'email' => $fak->email,
            'email_verified_at' => now(),
        ]);
        // Crea clientes ficticios
        for ($i=0; $i < 5; $i++) {
            \App\Models\Cliente::create([
                'nombre'=>$fak->company,
                'telefono'=>$fak->phoneNumber,
            ]);
        }
        // Crea coches ficticios

        for ($i=0; $i < 5; $i++) {
            \App\Models\Coche::create([
                'matricula'=>$fak2->jpjNumberPlate,
            ]);
        }
        // Crea conductores ficticios
        for ($i=0; $i < 5; $i++) {
            \App\Models\Conductor::create([
                'nombre'=>$fak->firstName." ".$fak->lastName,
            ]);
        }
        // Crea entradas para agenda confirmadas y libro
        for ($i=1; $i <= 5; $i++) {

            $usuario = rand(1,2);
            $cliente = rand(1,5);
            $salida = $fak->dateTimeBetween($startDate = '-60 days', $endDate = '-50 days', $timezone = null);
            $llegada = $fak->dateTimeBetween($startDate = '-49 days', $endDate = '-45 days', $timezone = null);
            $sitio = $fak->city;
            $clienteDetalle = $fak->sentence($nbWords = 6, $variableNbWords = true);
            // Entradas de la agenda
            $entrada = \App\Models\AgendaEntrada::create([
                'idUsuario'=>$usuario,
                'salidaFecha'=>$salida->format('Y-m-d'),
                'salidaHora'=>$salida->format('H:i:s'),
                'salidaLugar'=>$sitio,
                'llegadaFecha'=>$llegada->format('Y-m-d'),
                'llegadaHora'=>$llegada->format('H:i:s'),
                'llegadaLugar'=>$sitio,
                'idCliente'=>$cliente,
                'clienteDetalle'=>$clienteDetalle,
                'confirmada'=>1
            ]);
            \App\Models\AgendaCoches::create([
                'idAgenda'=>$entrada->id,
                'idCoche' => $i
            ]);
            \App\Models\AgendaConductores::create([
                'idAgenda'=>$entrada->id,
                'idConductor' => $i
            ]);
            // Entradas del libro
            $entrada = \App\Models\LibroEntrada::create([
                'idAgenda'=>$entrada->id,
                'idUsuario'=>$usuario,
                'salidaFecha'=>$salida->format('Y-m-d'),
                'salidaHora'=>$salida->format('H:i:s'),
                'salidaLugar'=>$sitio,
                'llegadaFecha'=>$llegada->format('Y-m-d'),
                'llegadaHora'=>$llegada->format('H:i:s'),
                'llegadaLugar'=>$sitio,
                'idCliente'=>$cliente,
                'clienteDetalle'=>$clienteDetalle,
            ]);
            \App\Models\LibroCoches::create([
                'idLibro'=>$entrada->id,
                'idCoche' => $i
            ]);
            \App\Models\LibroConductores::create([
                'idLibro'=>$entrada->id,
                'idConductor' => $i
            ]);
        }
        for ($i=1; $i <= 5; $i++) {

            // Crea entradas para agenda
            $usuario = rand(1,2);
            $cliente = rand(1,5);
            $salida = $fak->dateTimeBetween($startDate = '-25 days', $endDate = '-20 days', $timezone = null);
            $llegada = $fak->dateTimeBetween($startDate = '-19 days', $endDate = '-15 days', $timezone = null);
            $sitio = $fak->city;
            $entrada = \App\Models\AgendaEntrada::create([
                'idUsuario'=>$usuario,
                'salidaFecha'=>$salida->format('Y-m-d'),
                'salidaHora'=>$salida->format('H:i:s'),
                'salidaLugar'=>$sitio,
                'llegadaFecha'=>$llegada->format('Y-m-d'),
                'llegadaHora'=>$llegada->format('H:i:s'),
                'llegadaLugar'=>$sitio,
                'idCliente'=>$cliente,
                'clienteDetalle'=>$fak->sentence($nbWords = 6, $variableNbWords = true),

            ]);
            \App\Models\AgendaCoches::create([
                'idAgenda'=>$entrada->id,
                'idCoche' => $i
            ]);
            \App\Models\AgendaConductores::create([
                'idAgenda'=>$entrada->id,
                'idConductor' => $i
            ]);
        }
    }
}

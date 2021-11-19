<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgendaHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $tablename = 'aviso_historico';
    public function up()
    {

        Schema::create($this->tablename, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('habilitado',3)->nullable();
            $table->string('confirmada',3)->nullable();
            $table->string('idUsuario',1000)->nullable();
            $table->string('salidaFecha',25)->nullable();
            $table->string('salidaHora',25)->nullable();
            $table->string('salidaLugar', 500)->nullable();
            $table->string('llegadaFecha',25)->nullable();
            $table->string('llegadaHora',25)->nullable();
            $table->string('llegadaLugar', 500)->nullable();
            $table->string('itinerario', 1000)->nullable();
            $table->string('idCliente',500)->nullable();
            $table->string('clienteDetalle', 500)->nullable();
            $table->string('observaciones', 1000)->nullable();
            $table->string('respuesta',100)->nullable();
            $table->string('respuestaFecha',25)->nullable();
            $table->string('respuestaDetalle', 1000)->nullable();
            $table->string('coches', 1000)->nullable();
            $table->string('idMigracion',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablename);
    }
}

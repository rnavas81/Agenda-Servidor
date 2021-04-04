<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateavisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aviso', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('habilitado')->default(1);
            $table->tinyInteger('confirmada')->default(0);
            $table->integer('idUsuario');
            $table->date('salidaFecha')->nullable();
            $table->time('salidaHora')->nullable();
            $table->string('salidaLugar', 500)->nullable()->default('');
            $table->date('llegadaFecha')->nullable();
            $table->time('llegadaHora')->nullable();
            $table->string('llegadaLugar', 500)->nullable()->default('');
            $table->string('itinerario', 1000)->nullable()->default('');
            $table->integer('idCliente')->nullable()->unsigned();
            $table->string('clienteDetalle', 500)->nullable()->default('');
            $table->string('observaciones', 1000)->nullable();
            $table->tinyInteger('respuesta')->default(0)->unsigned();
            $table->date('respuestaFecha')->nullable();
            $table->string('respuestaDetalle', 1000)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aviso');
    }
}

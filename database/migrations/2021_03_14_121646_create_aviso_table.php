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
            $table->string('salidaLugar', 500)->nullable();
            $table->date('llegadaFecha')->nullable();
            $table->time('llegadaHora')->nullable();
            $table->string('llegadaLugar', 500)->nullable();
            $table->string('itinerario', 1000)->nullable();
            $table->integer('idCliente')->nullable()->unsigned();
            $table->string('clienteDetalle', 500)->nullable();
            $table->float('presupuesto', 12, 3)->nullable();
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

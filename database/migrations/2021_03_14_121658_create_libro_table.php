<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libro', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('habilitado')->default(1);
            $table->integer('idUsuario');
            $table->integer('idAviso')->nullable();
            $table->date('salidaFecha')->nullable();
            $table->time('salidaHora')->nullable();
            $table->string('salidaLugar', 500)->nullable();
            $table->date('llegadaFecha')->nullable();
            $table->time('llegadaHora')->nullable();
            $table->string('llegadaLugar', 500)->nullable();
            $table->string('itinerario', 1000)->nullable();
            $table->float('kms', 12, 3)->nullable();
            $table->integer('idCliente')->nullable()->unsigned();
            $table->string('clienteDetalle', 500)->nullable();
            $table->integer('facturarA')->nullable()->unsigned();
            $table->string('contacto', 100)->nullable();
            $table->string('contactoTlf', 12)->nullable();
            $table->float('importe', 12, 2)->nullable();
            $table->tinyInteger('cobrado')->default(0);
            $table->date('cobradoFecha')->nullable();
            $table->string('cobradoForma', 255)->nullable();
            $table->string('cobradoDetalle', 500)->nullable();
            $table->string('gastos', 500)->nullable();
            $table->string('facturaNombre', 500)->nullable();
            $table->integer('facturaNumero')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libro');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LibroHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $tablename = 'libro_historico';
    public function up()
    {

        Schema::create($this->tablename, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('habilitado', 3)->nullable();
            $table->string('idUsuario', 1000)->nullable();
            $table->string('idAviso', 10)->nullable();
            $table->string('salidaFecha', 25)->nullable();
            $table->string('salidaHora', 25)->nullable();
            $table->string('salidaLugar', 1000)->nullable();
            $table->string('llegadaFecha', 25)->nullable();
            $table->string('llegadaHora', 25)->nullable();
            $table->string('llegadaLugar', 1000)->nullable();
            $table->string('itinerario', 1000)->nullable();
            $table->string('contacto', 100)->nullable();
            $table->string('contactoTlf', 12)->nullable();
            $table->string('kms', 25)->nullable();
            $table->string('idCliente', 500)->nullable();
            $table->string('clienteDetalle', 500)->nullable();
            $table->string('observaciones', 1000)->nullable();
            $table->string('importe', 25)->nullable();
            $table->string('cobrado', 3)->nullable();
            $table->string('cobradoFecha', 25)->nullable();
            $table->string('cobradoForma', 255)->nullable();
            $table->string('cobradoDetalle', 500)->nullable();
            $table->string('gastos', 1000)->nullable();
            $table->string('facturarA', 500)->nullable();
            $table->string('facturaNombre', 500)->nullable();
            $table->string('facturaNumero', 25)->nullable();
            $table->string('idMigracion',50)->nullable();
            $table->string('conductores',1000)->nullable();
            $table->string('coches',1000)->nullable();
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

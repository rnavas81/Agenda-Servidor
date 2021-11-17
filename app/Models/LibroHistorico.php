<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LibroHistorico extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'libro_historico';
    protected $fillable = [
        "habilitado",
        "idUsuario",
        "idAviso",
        "salidaFecha",
        "salidaHora",
        "salidaLugar",
        "llegadaFecha",
        "llegadaHora",
        "llegadaLugar",
        "itinerario",
        "contacto",
        "contactoTlf",
        "kms",
        "idCliente",
        "clienteDetalle",
        "observaciones",
        "importe",
        "cobrado",
        "cobradoFecha",
        "cobradoForma",
        "cobradoDetalle",
        "gastos",
        "facturarA",
        "facturaNombre",
        "facturaNumero",
        "idMigracion",
        "conductores",
        "coches",
    ];
}

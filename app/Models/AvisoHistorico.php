<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisoHistorico extends Model
{
    use HasFactory;
    protected $table = 'aviso_historico';
    protected $fillable = [
        'habilitado',
        'confirmada',
        'idUsuario',
        'salidaFecha',
        'salidaHora',
        'salidaLugar',
        'llegadaFecha',
        'llegadaHora',
        'llegadaLugar',
        'itinerario',
        'idCliente',
        'clienteDetalle',
        'observaciones',
        'respuesta',
        'respuestaFecha',
        'respuestaDetalle',
        'idMigracion'
    ];
}

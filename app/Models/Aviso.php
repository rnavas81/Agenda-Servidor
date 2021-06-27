<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Aviso extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'aviso';
    protected $fillable = [
        'salidaFecha',
        'salidaHora',
        'salidaLugar',
        'llegadaFecha',
        'llegadaHora',
        'llegadaLugar',
        'itinerario',
        'clienteDetalle',
        'observaciones',
        'idCliente',
        'idUsuario',
        'respuesta',
        'respuestaDetalle'
    ];
    protected $hidden = [
        'idCliente',
        'idUsuario',
        'updated_at',
        'habilitado',
    ];

    public function usuario()
    {
        return $this->hasOne(User::class, 'id', 'idUsuario');
    }
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id', 'idCliente');
    }
    public function coches()
    {
        return $this->hasMany(AvisoCoches::class, 'idAviso', 'id');
    }
    // public function conductores()
    // {
    //     return $this->hasMany(AvisoConductores::class, 'idAviso', 'id');
    // }
}

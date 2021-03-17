<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AgendaEntrada extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'agenda';
    protected $fillable = [
        'usuario',
        'salidaFecha',
        'salidaHora',
        'salidaLugar',
        'llegadaFecha',
        'llegadaHora',
        'llegadaLugar',
        'itinerario',
        'cliente',
        'clienteDetalle',
        'presupuesto',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'habilitado',
        'aprobado'
    ];

    public function usuario() {
        return $this->hasOne('App\Models\User','usuario','id');
    }
    public function cliente() {
        return $this->hasOne('App\Models\Cliente','cliente','id');
    }
    public function coches() {
        return $this->hasMany('App\Models\Agenda-Coches','id','idCoche');
    }
    public function conductores() {
        return $this->hasMany('App\Models\Agenda-Coches','id','idConductor');
    }
}

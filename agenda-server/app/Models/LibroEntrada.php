<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LibroEntrada extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'libro';
    protected $fillable = [
        'idAgenda',
        'salidaFecha',
        'salidaHora',
        'salidaLugar',
        'llegadaFecha',
        'llegadaHora',
        'llegadaLugar',
        'itinerario',
        'kms',
        'cliente',
        'clienteDetalle',
        'facturarA',
        'contacto',
        'contactoTlf',
        'importe',
        'cobrado',
        'cobradoFecha',
        'cobradoForma',
        'cobradoDetalle',
        'gastos',
        'facturaNombre',
        'facturaNumero',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'habilitado'
    ];
    public function usuario() {
        return $this->hasOne('App\Models\User','usuario','id');
    }
    public function cliente() {
        return $this->hasOne('App\Models\Cliente','cliente','id');
    }
    public function facturarA() {
        return $this->hasOne('App\Models\Cliente','facturarA','id');
    }
    public function coches() {
        return $this->hasMany('App\Models\Libro-Coches','id','idCoche');
    }
    public function conductores() {
        return $this->hasMany('App\Models\Libro-Conductores','id','idConductor');
    }
}

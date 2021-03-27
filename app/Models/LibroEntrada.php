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
        'idCliente',
        'idUsuario',
        'idAgenda',
        'salidaFecha',
        'salidaHora',
        'salidaLugar',
        'llegadaFecha',
        'llegadaHora',
        'llegadaLugar',
        'itinerario',
        'kms',
        'idCliente',
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
        'idCliente',
        'idUsuario',
        'created_at',
        'updated_at',
        'habilitado'
    ];
    public function agenda()
    {
        return $this->hasOne(AgendaEntrada::class, 'id', 'idAgenda');
    }
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
        return $this->hasMany(LibroCoches::class, 'idLibro', 'id');
    }
    public function conductores()
    {
        return $this->hasMany(LibroConductores::class, 'idLibro', 'id');
    }
}

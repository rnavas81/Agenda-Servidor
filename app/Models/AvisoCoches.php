<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class AvisoCoches extends Model
{
    use HasFactory, Notifiable;
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'aviso-coches';

    protected $fillable = [
        'idAviso',
        'idCoche',
        'presupuesto'
    ];
    protected $hidden = [
        'idAviso',
        'idCoche'
    ];
    public function aviso()
    {
        return $this->hasOne(AvisoEntrada::class, 'idAviso', 'id');
    }
    public function coche()
    {
        return $this->belongsTo(Coche::class, 'idCoche', 'id');
    }
}

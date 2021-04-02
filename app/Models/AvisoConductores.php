<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AvisoConductores extends Model
{
    use HasFactory, Notifiable;
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'aviso-conductores';
    protected $fillable = [
        'idAviso',
        'idConductor'
    ];
    protected $hidden = [
        'idAviso',
        'idConductor'
    ];
    public function aviso()
    {
        return $this->hasOne(AvisoEntrada::class, 'idAviso', 'id');
    }
    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'idConductor', 'id');
    }
}

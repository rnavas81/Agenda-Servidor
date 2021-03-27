<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AgendaConductores extends Model
{
    use HasFactory, Notifiable;
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'agenda-conductores';
    protected $fillable = [
        'idAgenda',
        'idConductor'
    ];
    protected $hidden = [
        'idAgenda',
        'idConductor'
    ];
    public function agenda()
    {
        return $this->hasOne(AgendaEntrada::class, 'idAgenda', 'id');
    }
    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'idConductor', 'id');
    }
}

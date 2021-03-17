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
    public function agenda() {
        $this->hasOne('App\Models\Agenda-Entrada','idAgenda','id');
    }
    public function conductor() {
        $this->hasOne('App\Models\Conductor','idConductor','id');
    }
}


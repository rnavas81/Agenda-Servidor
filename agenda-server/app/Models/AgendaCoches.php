<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class AgendaCoches extends Model
{
    use HasFactory, Notifiable;
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'agenda-coches';

    protected $fillable = [
        'idAgenda',
        'idCoche'
    ];
    public function agenda() {
        $this->hasOne('App\Models\Agenda-Entrada','idAgenda','id');
    }
    public function coche() {
        $this->hasOne('App\Models\Agenda-Entrada','idCoche','id');
    }
}

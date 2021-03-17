<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LibroConductores extends Model
{
    use HasFactory, Notifiable;
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'libro-conductores';
    protected $fillable = [
        'idLibro',
        'idConductor'
    ];
    public function agenda() {
        $this->hasOne('App\Models\Agenda-Entrada','idLibro','id');
    }
    public function coche() {
        $this->hasOne('App\Models\Condcutor','idConductor','id');
    }
}

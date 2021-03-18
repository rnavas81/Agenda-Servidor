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
    protected $hidden = [
        'idAgenda',
        'idCoche'
    ];
    public function agenda()
    {
        return $this->hasOne(AgendaEntrada::class, 'idAgenda', 'id');
    }
    public function coche()
    {
        return $this->belongsTo(Coche::class, 'idCoche', 'id');
    }
}

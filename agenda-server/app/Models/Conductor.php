<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Conductor extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'conductores';
    protected $fillable = [
        'habilitado',
        'nombre',
    ];
}

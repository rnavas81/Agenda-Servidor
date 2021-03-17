<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cliente extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'habilitado',
        'nombre',
        'telefono',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'habilitado',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LibroCoches extends Model
{
    use HasFactory, Notifiable;
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'libro-coches';
    protected $fillable = [
        'idLibro',
        'idCoche'
    ];
    protected $hidden = [
        'idLibro',
        'idCoche'
    ];
    public function libro()
    {
        return $this->hasOne(LibroEntrada::class, 'idLibro', 'id');
    }
    public function coche()
    {
        return $this->belongsTo(Coche::class, 'idCoche', 'id');
    }
}


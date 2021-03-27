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
    protected $hidden = [
        'idLibro',
        'idConductor'
    ];
    public function libro()
    {
        return $this->hasOne(LibroEntrada::class, 'idLibro', 'id');
    }
    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'idConductor', 'id');
    }
}

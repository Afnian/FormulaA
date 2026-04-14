<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemaPuntos extends Model
{
    use HasFactory;

    protected $table = 'sistema_puntos';

    protected $fillable = [
        'id_temporada',
        'posicion',
        'puntos',
    ];

    protected $casts = [
        'posicion' => 'integer',
        'puntos'   => 'integer',
    ];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada');
    }
}
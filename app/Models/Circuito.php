<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuito extends Model
{
    use HasFactory;

    protected $table = 'circuitos';

    protected $fillable = [
        'nombre',
        'pais',
        'imagen_url',
        'num_vueltas',
    ];

    protected $casts = [
        'num_vueltas' => 'integer',
    ];

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_circuito');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    use HasFactory;

    protected $table = 'temporadas';

    protected $fillable = [
        'nombre',
        'categoria',
        'anio',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'anio'   => 'integer',
    ];

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_temporada');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionPiloto::class, 'id_temporada');
    }

    public function sistemaPuntos()
    {
        return $this->hasMany(SistemaPuntos::class, 'id_temporada');
    }
}
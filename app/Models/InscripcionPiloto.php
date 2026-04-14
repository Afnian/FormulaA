<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscripcionPiloto extends Model
{
    use HasFactory;

    protected $table = 'inscripciones_piloto';

    protected $fillable = [
        'id_piloto',
        'id_escuderia',
        'id_temporada',
        'tipo',
    ];

    public function piloto()
    {
        return $this->belongsTo(Piloto::class, 'id_piloto');
    }

    public function escuderia()
    {
        return $this->belongsTo(Escuderia::class, 'id_escuderia');
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada');
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'id_inscripcion');
    }
}
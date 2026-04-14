<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'resultados';

    protected $fillable = [
        'id_evento',
        'id_inscripcion',
        'posicion',
        'diferencia',
        'pts_carrera',
        'pts_vuelta_rap',
        'pts_pole',
        'dnf',
    ];

    protected $casts = [
        'posicion'      => 'integer',
        'pts_carrera'   => 'integer',
        'pts_vuelta_rap'=> 'integer',
        'pts_pole'      => 'integer',
        'dnf'           => 'boolean',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function inscripcion()
    {
        return $this->belongsTo(InscripcionPiloto::class, 'id_inscripcion');
    }

    public function getPuntajeTotal(): int
    {
        return $this->pts_carrera + $this->pts_vuelta_rap + $this->pts_pole;
    }
}
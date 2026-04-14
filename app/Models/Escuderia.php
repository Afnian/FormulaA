<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escuderia extends Model
{
    use HasFactory;

    protected $table = 'escuderias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'logo_url',
    ];

    public function inscripciones()
    {
        return $this->hasMany(InscripcionPiloto::class, 'id_escuderia');
    }

    public function pilotos()
    {
        return $this->belongsToMany(Piloto::class, 'inscripciones_piloto', 'id_escuderia', 'id_piloto')
                    ->withPivot('id_temporada', 'tipo')
                    ->withTimestamps();
    }
}
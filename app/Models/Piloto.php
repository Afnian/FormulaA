<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piloto extends Model
{
    use HasFactory;

    protected $table = 'pilotos';

    protected $fillable = [
        'id_usuario',
        'gamertag',
        'nacionalidad',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionPiloto::class, 'id_piloto');
    }

    public function escuderias()
    {
        return $this->belongsToMany(Escuderia::class, 'inscripciones_piloto', 'id_piloto', 'id_escuderia')
                    ->withPivot('id_temporada', 'tipo')
                    ->withTimestamps();
    }
}
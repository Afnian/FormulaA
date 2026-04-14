<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'id_temporada',
        'id_circuito',
        'nombre',
        'ronda',
        'fecha',
        'completado',
    ];

    protected $casts = [
        'fecha'      => 'datetime',
        'completado' => 'boolean',
        'ronda'      => 'integer',
    ];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada');
    }

    public function circuito()
    {
        return $this->belongsTo(Circuito::class, 'id_circuito');
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'id_evento');
    }

    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'id_evento');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = [
        'id_evento',
        'id_autor',
        'titulo',
        'contenido',
        'estado',
        'publicado_en',
    ];

    protected $casts = [
        'publicado_en' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'id_autor');
    }

    public function scopePublicadas($query)
    {
        return $query->where('estado', 'publicada');
    }

    public function scopeBorradores($query)
    {
        return $query->where('estado', 'borrador');
    }
}
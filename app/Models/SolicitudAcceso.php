<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAcceso extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_acceso';

    protected $fillable = [
        'id_usuario',
        'fecha_solicitud',
        'estado',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
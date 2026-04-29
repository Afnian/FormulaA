<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method bool hasRole(string $rol)
 * @method bool hasAnyRole(array $roles)
 *
 * @property int    $id
 * @property string $nombre
 * @property string $email
 * @property string $rol
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function piloto()
    {
        return $this->hasOne(Piloto::class, 'id_usuario');
    }

    public function noticias()
    {
        return $this->hasMany(Noticias::class, 'id_autor');
    }

    public function solicitudAcceso()
    {
        return $this->hasOne(SolicitudAcceso::class, 'id_usuario');
    }

    public function hasRole(string $rol): bool
    {
        return $this->rol === $rol;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->rol, $roles);
    }
}
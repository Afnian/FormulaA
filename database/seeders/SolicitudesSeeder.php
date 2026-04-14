<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SolicitudAcceso;
use App\Models\User;

class SolicitudesSeeder extends Seeder
{
    public function run(): void
    {
        $espectador = User::where('email', 'espectador@formulaa.com')->first();

        SolicitudAcceso::create([
            'id_usuario'      => $espectador->id,
            'fecha_solicitud' => now(),
            'estado'          => 'pendiente',
        ]);
    }
}
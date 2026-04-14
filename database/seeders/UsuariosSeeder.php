<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nombre'   => 'Administrador',
            'email'    => 'admin@formulaa.com',
            'password' => Hash::make('password123'),
            'rol'      => 'admin',
        ]);

        User::create([
            'nombre'   => 'Editor Noticias',
            'email'    => 'editor@formulaa.com',
            'password' => Hash::make('password123'),
            'rol'      => 'editor',
        ]);

        User::create([
            'nombre'   => 'Carlos Rueda',
            'email'    => 'carlos@formulaa.com',
            'password' => Hash::make('password123'),
            'rol'      => 'piloto',
        ]);

        User::create([
            'nombre'   => 'Marcos Velasco',
            'email'    => 'marcos@formulaa.com',
            'password' => Hash::make('password123'),
            'rol'      => 'piloto',
        ]);

        User::create([
            'nombre'   => 'Lucía Navarro',
            'email'    => 'lucia@formulaa.com',
            'password' => Hash::make('password123'),
            'rol'      => 'piloto',
        ]);

        User::create([
            'nombre'   => 'Espectador Demo',
            'email'    => 'espectador@formulaa.com',
            'password' => Hash::make('password123'),
            'rol'      => 'espectador',
        ]);
    }
}
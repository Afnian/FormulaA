<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsuariosSeeder::class,
            EscuderiasSeeder::class,
            TemporadasSeeder::class,
            PilotosSeeder::class,
            CircuitosSeeder::class,
            SistemaPuntosSeeder::class,
            InscripcionesSeeder::class,
            EventosSeeder::class,
            ResultadosSeeder::class,
            NoticiasSeeder::class,
            SolicitudesSeeder::class,
        ]);
    }
}
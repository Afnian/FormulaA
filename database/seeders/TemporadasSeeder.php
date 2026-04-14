<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Temporada;

class TemporadasSeeder extends Seeder
{
    public function run(): void
    {
        Temporada::create([
            'nombre'    => 'Fórmula A 2025',
            'categoria' => 'formula_a',
            'anio'      => 2025,
            'activa'    => true,
        ]);

        Temporada::create([
            'nombre'    => 'Fórmula B 2025',
            'categoria' => 'formula_b',
            'anio'      => 2025,
            'activa'    => true,
        ]);
    }
}
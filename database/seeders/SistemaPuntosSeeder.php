<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SistemaPuntos;
use App\Models\Temporada;

class SistemaPuntosSeeder extends Seeder
{
    public function run(): void
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')->first();
        $temporadaFB = Temporada::where('categoria', 'formula_b')->first();

        // Puntuación Fórmula A (carrera principal)
        $puntosFA = [
            1 => 25, 2 => 18, 3 => 15, 4 => 12, 5 => 10,
            6 => 8,  7 => 6,  8 => 4,  9 => 2,  10 => 1,
        ];

        foreach ($puntosFA as $posicion => $puntos) {
            SistemaPuntos::create([
                'id_temporada' => $temporadaFA->id,
                'posicion'     => $posicion,
                'puntos'       => $puntos,
            ]);
        }

        // Puntuación Fórmula B (carrera sprint)
        $puntosFB = [
            1 => 13, 2 => 9, 3 => 8, 4 => 6, 5 => 5,
            6 => 4,  7 => 3, 8 => 2, 9 => 1,
        ];

        foreach ($puntosFB as $posicion => $puntos) {
            SistemaPuntos::create([
                'id_temporada' => $temporadaFB->id,
                'posicion'     => $posicion,
                'puntos'       => $puntos,
            ]);
        }
    }
}
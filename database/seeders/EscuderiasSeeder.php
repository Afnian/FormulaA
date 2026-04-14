<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Escuderia;

class EscuderiasSeeder extends Seeder
{
    public function run(): void
    {
        Escuderia::create([
            'nombre'      => 'Scuderia Rossa',
            'descripcion' => 'Escudería italiana con décadas de historia en la competición. Velocidad y pasión por encima de todo.',
            'logo_url'    => null,
        ]);

        Escuderia::create([
            'nombre'      => 'Apex Racing',
            'descripcion' => 'Equipo técnicamente innovador, siempre al límite de la reglamentación. Sus ingenieros no duermen.',
            'logo_url'    => null,
        ]);

        Escuderia::create([
            'nombre'      => 'Silver Arrows Team',
            'descripcion' => 'La consistencia es su bandera. Pocas victorias, pero nunca se rompen.',
            'logo_url'    => null,
        ]);
    }
}
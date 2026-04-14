<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\Temporada;
use App\Models\Circuito;

class EventosSeeder extends Seeder
{
    public function run(): void
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')->first();

        $monaco    = Circuito::where('nombre', 'Circuit de Monaco')->first();
        $monza     = Circuito::where('nombre', 'Autodromo Nazionale Monza')->first();
        $barcelona = Circuito::where('nombre', 'Circuit de Barcelona-Catalunya')->first();

        Evento::create([
            'id_temporada' => $temporadaFA->id,
            'id_circuito'  => $monaco->id,
            'nombre'       => 'Gran Premio de Mónaco',
            'ronda'        => 1,
            'fecha'        => '2025-03-15 15:00:00',
            'completado'   => true,
        ]);

        Evento::create([
            'id_temporada' => $temporadaFA->id,
            'id_circuito'  => $monza->id,
            'nombre'       => 'Gran Premio de Italia',
            'ronda'        => 2,
            'fecha'        => '2025-04-20 15:00:00',
            'completado'   => true,
        ]);

        Evento::create([
            'id_temporada' => $temporadaFA->id,
            'id_circuito'  => $barcelona->id,
            'nombre'       => 'Gran Premio de España',
            'ronda'        => 3,
            'fecha'        => '2025-06-01 15:00:00',
            'completado'   => false,
        ]);
    }
}
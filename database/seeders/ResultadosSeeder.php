<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resultado;
use App\Models\Evento;
use App\Models\InscripcionPiloto;
use App\Models\Piloto;

class ResultadosSeeder extends Seeder
{
    public function run(): void
    {
        $monaco = Evento::where('nombre', 'Gran Premio de Mónaco')->first();
        $monza  = Evento::where('nombre', 'Gran Premio de Italia')->first();

        $carlos = Piloto::where('gamertag', 'CarlosR44')->first();
        $marcos = Piloto::where('gamertag', 'MarcosV7')->first();
        $lucia  = Piloto::where('gamertag', 'LuciaSpeed')->first();

        $inscCarlos = InscripcionPiloto::where('id_piloto', $carlos->id)->first();
        $inscMarcos = InscripcionPiloto::where('id_piloto', $marcos->id)->first();
        $inscLucia  = InscripcionPiloto::where('id_piloto', $lucia->id)->first();

        // --- Ronda 1: Mónaco ---
        // Carlos P1 + pole + vuelta rápida = 25 + 2 + 1 = 28
        Resultado::create([
            'id_evento'      => $monaco->id,
            'id_inscripcion' => $inscCarlos->id,
            'posicion'       => 1,
            'diferencia'     => 'LÍDER',
            'pts_carrera'    => 25,
            'pts_pole'       => 2,
            'pts_vuelta_rap' => 1,
            'dnf'            => false,
        ]);

        // Marcos P2 = 18
        Resultado::create([
            'id_evento'      => $monaco->id,
            'id_inscripcion' => $inscMarcos->id,
            'posicion'       => 2,
            'diferencia'     => '+5.432s',
            'pts_carrera'    => 18,
            'pts_pole'       => 0,
            'pts_vuelta_rap' => 0,
            'dnf'            => false,
        ]);

        // Lucía P3 = 15
        Resultado::create([
            'id_evento'      => $monaco->id,
            'id_inscripcion' => $inscLucia->id,
            'posicion'       => 3,
            'diferencia'     => '+12.871s',
            'pts_carrera'    => 15,
            'pts_pole'       => 0,
            'pts_vuelta_rap' => 0,
            'dnf'            => false,
        ]);

        // --- Ronda 2: Monza ---
        // Marcos P1 + pole = 25 + 2 = 27
        Resultado::create([
            'id_evento'      => $monza->id,
            'id_inscripcion' => $inscMarcos->id,
            'posicion'       => 1,
            'diferencia'     => 'LÍDER',
            'pts_carrera'    => 25,
            'pts_pole'       => 2,
            'pts_vuelta_rap' => 0,
            'dnf'            => false,
        ]);

        // Lucía P2 + vuelta rápida = 18 + 1 = 19
        Resultado::create([
            'id_evento'      => $monza->id,
            'id_inscripcion' => $inscLucia->id,
            'posicion'       => 2,
            'diferencia'     => '+3.210s',
            'pts_carrera'    => 18,
            'pts_pole'       => 0,
            'pts_vuelta_rap' => 1,
            'dnf'            => false,
        ]);

        // Carlos DNF = 0
        Resultado::create([
            'id_evento'      => $monza->id,
            'id_inscripcion' => $inscCarlos->id,
            'posicion'       => null,
            'diferencia'     => 'DNF',
            'pts_carrera'    => 0,
            'pts_pole'       => 0,
            'pts_vuelta_rap' => 0,
            'dnf'            => true,
        ]);
    }
}
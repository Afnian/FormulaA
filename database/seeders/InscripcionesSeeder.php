<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InscripcionPiloto;
use App\Models\Piloto;
use App\Models\Escuderia;
use App\Models\Temporada;

class InscripcionesSeeder extends Seeder
{
    public function run(): void
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')->first();

        $carlos  = Piloto::where('gamertag', 'CarlosR44')->first();
        $marcos  = Piloto::where('gamertag', 'MarcosV7')->first();
        $lucia   = Piloto::where('gamertag', 'LuciaSpeed')->first();

        $scuderiaRossa  = Escuderia::where('nombre', 'Scuderia Rossa')->first();
        $apexRacing     = Escuderia::where('nombre', 'Apex Racing')->first();
        $silverArrows   = Escuderia::where('nombre', 'Silver Arrows Team')->first();

        InscripcionPiloto::create([
            'id_piloto'    => $carlos->id,
            'id_escuderia' => $scuderiaRossa->id,
            'id_temporada' => $temporadaFA->id,
            'tipo'         => 'oficial',
        ]);

        InscripcionPiloto::create([
            'id_piloto'    => $marcos->id,
            'id_escuderia' => $apexRacing->id,
            'id_temporada' => $temporadaFA->id,
            'tipo'         => 'oficial',
        ]);

        InscripcionPiloto::create([
            'id_piloto'    => $lucia->id,
            'id_escuderia' => $silverArrows->id,
            'id_temporada' => $temporadaFA->id,
            'tipo'         => 'oficial',
        ]);
    }
}
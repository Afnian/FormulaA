<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Circuito;

class CircuitosSeeder extends Seeder
{
    public function run(): void
    {
        Circuito::create([
            'nombre'     => 'Circuit de Monaco',
            'pais'       => 'Mónaco',
            'imagen_url' => null,
            'num_vueltas'=> 78,
        ]);

        Circuito::create([
            'nombre'     => 'Autodromo Nazionale Monza',
            'pais'       => 'Italia',
            'imagen_url' => null,
            'num_vueltas'=> 53,
        ]);

        Circuito::create([
            'nombre'     => 'Circuit de Barcelona-Catalunya',
            'pais'       => 'España',
            'imagen_url' => null,
            'num_vueltas'=> 66,
        ]);
    }
}
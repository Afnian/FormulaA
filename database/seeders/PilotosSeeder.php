<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Piloto;
use App\Models\User;

class PilotosSeeder extends Seeder
{
    public function run(): void
    {
        $pilotos = [
            'carlos@formulaa.com'  => ['gamertag' => 'CarlosR44',    'nacionalidad' => 'Española'],
            'marcos@formulaa.com'  => ['gamertag' => 'MarcosV7',     'nacionalidad' => 'Española'],
            'lucia@formulaa.com'   => ['gamertag' => 'LuciaSpeed',   'nacionalidad' => 'Española'],
        ];

        foreach ($pilotos as $email => $datos) {
            $usuario = User::where('email', $email)->first();
            Piloto::create([
                'id_usuario'   => $usuario->id,
                'gamertag'     => $datos['gamertag'],
                'nacionalidad' => $datos['nacionalidad'],
            ]);
        }
    }
}
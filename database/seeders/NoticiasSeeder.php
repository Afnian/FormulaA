<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Noticias;
use App\Models\User;
use App\Models\Evento;

class NoticiasSeeder extends Seeder
{
    public function run(): void
    {
        $editor = User::where('email', 'editor@formulaa.com')->first();
        $monaco = Evento::where('nombre', 'Gran Premio de Mónaco')->first();
        $monza  = Evento::where('nombre', 'Gran Premio de Italia')->first();

        Noticias::create([
            'id_evento'    => $monaco->id,
            'id_autor'     => $editor->id,
            'titulo'       => 'CarlosR44 domina en Mónaco y lidera el mundial',
            'contenido'    => 'En una carrera impecable por las calles del Principado, CarlosR44 se impuso desde la pole position y suma 28 puntos en la primera ronda de la temporada. MarcosV7 terminó segundo y LuciaSpeed cerró el podio en tercera posición.',
            'estado'       => 'publicada',
            'publicado_en' => '2025-03-15 18:30:00',
        ]);

        Noticias::create([
            'id_evento'    => $monza->id,
            'id_autor'     => $editor->id,
            'titulo'       => 'MarcosV7 vence en Monza y recorta en el campeonato',
            'contenido'    => 'El piloto de Apex Racing se llevó la victoria en el Templo de la Velocidad tras una salida perfecta desde la pole. LuciaSpeed sumó el punto extra de vuelta rápida. CarlosR44 abandonó por fallo mecánico.',
            'estado'       => 'publicada',
            'publicado_en' => '2025-04-20 18:00:00',
        ]);

        Noticias::create([
            'id_evento'    => null,
            'id_autor'     => $editor->id,
            'titulo'       => 'Previa del Gran Premio de España: todo por decidir en Barcelona',
            'contenido'    => 'Con solo dos puntos de diferencia en el mundial, el Circuit de Barcelona-Catalunya acogerá el tercer asalto de la temporada. Análisis de los tres contendientes al título.',
            'estado'       => 'borrador',
            'publicado_en' => null,
        ]);
    }
}
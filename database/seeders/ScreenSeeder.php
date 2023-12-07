<?php

namespace Database\Seeders;

use App\Models\Screen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Screen::create([
            'nombre' => "Bunge Centro",
            'direccion' => "Av. Arq Bunge 547, esq. Av. del Libertador",
            'imagen' => "../images/screen/bungecentro.png",
            'hora_encendido' => '6:00',
            'horario_apagado' => '2:00',
            'url_google_maps'
            => "todavia no disponible",
            'proximo_horario_disponible' => '20 minutos',
            'ultimo_dia_compra' => "30/03/2024 - 23:59hs GTM-3",
            'aspect_ratio' => "4:3",
            'dimension_px' => '{"width":"1030px","height":"800px"}',
            'dimension_mts_marco' => '{ "ancho": "4 MTS", "alto": "3 MTS" }',
            'dimension_mts_pantalla' => '{ "ancho": "4 MTS", "alto": "3 MTS" }',
        ]);

        Screen::create([
            'nombre' => "Bunge al Mar",
            'direccion' => "Av. Arq Bunge 488, esq. Av. del Libertador",
            'imagen' => "../images/screen/bungealmar.png",
            'hora_encendido' => '6:00',
            'horario_apagado' => '2:00',
            'url_google_maps'
            => "todavia no disponible",
            'proximo_horario_disponible' => '20 minutos',
            'ultimo_dia_compra' => "30/03/2024 - 23:59hs GTM-3",
            'aspect_ratio' => "4:3",
            'dimension_px' => '{"width":"1030px","height":"800px"}',
            'dimension_mts_marco' => '{ "ancho": "4 MTS", "alto": "3 MTS" }',
            'dimension_mts_pantalla' => '{ "ancho": "4 MTS", "alto": "3 MTS" }',
        ]);
    }
}

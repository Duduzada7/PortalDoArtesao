<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Especialidades;

class EspecialidadeSeeder extends Seeder
{
    public function run(): void
    {
        $lista = [
            'Culinária',
            'Cerâmica',
            'Tecidos e Costura',
            'Escultura e Madeira',
            'Pintura e Desenho',
            'Bijuterias e Joias',
            'Reciclagem e Ecoarte',
            'Couro e Calçados',
        ];

        foreach ($lista as $nome) {
            Especialidades::firstOrCreate(['Nome' => $nome]);
        }
    }
}
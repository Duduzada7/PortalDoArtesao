<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ArtesaoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artesao')->insert([
            [
                'Nome' => 'Carlos Ferreira',
                'Telefone' => '31999998888',
                'Email' => 'carlos@email.com',
                'Rua' => 'Rua das Flores',
                'Numero' => '102',
                'Bairro' => 'Centro',
                'Nivel' => 'Iniciante',
                'StatusAprovacao' => 'aprovado',
                'posicao_fila' => 1,
                'created_at' => now(),
            ],
            [
                'Nome' => 'Ana Lima',
                'Telefone' => '31988887777',
                'Email' => 'ana@email.com',
                'Rua' => 'Av. Principal',
                'Numero' => '50',
                'Bairro' => 'Rosário',
                'Nivel' => 'Profissional',
                'StatusAprovacao' => 'aprovado',
                'posicao_fila' => 2,
                'created_at' => now(),
            ],
            [
                'Nome' => 'Pedro Almeida',
                'Telefone' => '31977776666',
                'Email' => 'pedro@email.com',
                'Rua' => 'Rua São José',
                'Numero' => '301',
                'Bairro' => 'Praia',
                'Nivel' => 'Master',
                'StatusAprovacao' => 'aprovado',
                'posicao_fila' => 3,
                'created_at' => now(),
            ],
            [
                'Nome' => 'Artesão Teste',
                'Email' => 'teste@email.com',
                'Telefone' => '31999999999',
                'Senha' => Hash::make('123456'), // Senha definida como 123456
                'StatusAprovacao' => 'aprovado',
                'posicao_fila' => 1,
                'created_at' => now(),
            ]
        ]);
    }
}
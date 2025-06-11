<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuário admin
        User::create([
            'usuario' => 'admin',
            'email' => 'admin@admin.com.br',
            'senha' => Hash::make('admin123'),
            'cpfCnpj' => '00000000000',
            'ativo' => 1,
            'telefone' => '11999999999',
        ]);

        // Usuário de teste
        User::create([
            'usuario' => 'teste',
            'email' => 'teste@teste.com',
            'senha' => Hash::make('teste123'),
            'cpfCnpj' => '11111111111',
            'ativo' => 1,
            'telefone' => '11888888888',
        ]);
    }
}

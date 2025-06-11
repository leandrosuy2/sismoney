<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPassword extends Command
{
    protected $signature = 'user:update-password {email} {password}';
    protected $description = 'Atualiza a senha de um usuário';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuário não encontrado!");
            return;
        }

        $user->senha = Hash::make($password);
        $user->save();

        $this->info("Senha atualizada com sucesso!");
        $this->info("Hash gerado: " . $user->senha);
    }
}

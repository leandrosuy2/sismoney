<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;

class UpdateLoanPhone extends Command
{
    protected $signature = 'loan:update-phone {loan_id} {phone}';
    protected $description = 'Atualiza o número de telefone de um empréstimo específico';

    public function handle()
    {
        $loanId = $this->argument('loan_id');
        $phone = $this->argument('phone');

        $loan = Loan::find($loanId);

        if (!$loan) {
            $this->error("Empréstimo ID {$loanId} não encontrado!");
            return;
        }

        $this->info("Atualizando número do empréstimo ID {$loanId}");
        $this->info("Cliente: {$loan->nome}");
        $this->info("Número atual: {$loan->telefone}");
        $this->info("Novo número: {$phone}");

        if ($this->confirm('Deseja confirmar esta alteração?')) {
            $loan->telefone = $phone;
            $loan->save();

            $this->info("Número atualizado com sucesso!");
        } else {
            $this->info("Operação cancelada.");
        }
    }
}

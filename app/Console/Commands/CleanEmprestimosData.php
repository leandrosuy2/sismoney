<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Emprestimo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanEmprestimosData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emprestimos:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa dados inconsistentes na tabela de empréstimos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpeza dos dados de empréstimos...');

        // 1. Corrigir datas inválidas (0000-00-00)
        $emprestimosComDataInvalida = Emprestimo::where('dataPagamento', '0000-00-00')->get();
        $this->info("Encontrados {$emprestimosComDataInvalida->count()} empréstimos com data inválida");

        foreach ($emprestimosComDataInvalida as $emprestimo) {
            // Definir uma data padrão (30 dias a partir de hoje)
            $dataPadrao = Carbon::now()->addDays(30)->format('Y-m-d');
            $emprestimo->update(['dataPagamento' => $dataPadrao]);
            $this->line("Empréstimo ID {$emprestimo->id}: data corrigida para {$dataPadrao}");
        }

        // 2. Corrigir status inválidos
        $statusValidos = ['pendente', 'pago'];
        $emprestimosComStatusInvalido = Emprestimo::whereNotIn('status', $statusValidos)->get();
        $this->info("Encontrados {$emprestimosComStatusInvalido->count()} empréstimos com status inválido");

        foreach ($emprestimosComStatusInvalido as $emprestimo) {
            // Se a data de pagamento já passou, marcar como pago, senão como pendente
            $statusCorreto = Carbon::parse($emprestimo->dataPagamento)->isPast() ? 'pago' : 'pendente';
            $emprestimo->update(['status' => $statusCorreto]);
            $this->line("Empréstimo ID {$emprestimo->id}: status corrigido para {$statusCorreto}");
        }

        // 3. Atualizar automaticamente empréstimos pendentes com data passada
        $emprestimosParaAtualizar = Emprestimo::where('status', 'pendente')
            ->where('dataPagamento', '!=', '0000-00-00')
            ->where('dataPagamento', '!=', null)
            ->where('dataPagamento', '<', Carbon::now())
            ->get();

        $this->info("Encontrados {$emprestimosParaAtualizar->count()} empréstimos pendentes com data passada");

        foreach ($emprestimosParaAtualizar as $emprestimo) {
            $emprestimo->update(['status' => 'pago']);
            $this->line("Empréstimo ID {$emprestimo->id}: marcado como pago (data: {$emprestimo->dataPagamento})");
        }

        $this->info('Limpeza concluída com sucesso!');

        // Mostrar resumo final
        $totalEmprestimos = Emprestimo::count();
        $pendentes = Emprestimo::where('status', 'pendente')->count();
        $pagos = Emprestimo::where('status', 'pago')->count();
        $comDataInvalida = Emprestimo::where('dataPagamento', '0000-00-00')->count();

        $this->info("Resumo final:");
        $this->info("- Total de empréstimos: {$totalEmprestimos}");
        $this->info("- Pendentes: {$pendentes}");
        $this->info("- Pagos: {$pagos}");
        $this->info("- Com data inválida: {$comDataInvalida}");
    }
}

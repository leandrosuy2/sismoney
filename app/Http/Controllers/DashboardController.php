<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->idUsuario;

        // Dinheiro na Rua Normal (empréstimos pendentes)
        $dinheiroNaRuaNormal = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->sum('valor');

        // Juros Mensais a Receber (Normais) - Cálculo correto dos juros diários
        $jurosMensaisNormais = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->sum(DB::raw('valor * (juros / 100) / 30')); // Calcula juros diários: (valor * porcentagem_juros) / 30 dias

        // Dinheiro na Rua Atrasado (Empréstimos + Contas a Receber)
        $emprestimosAtrasados = Emprestimo::where('status', 'atrasado')
            ->where('idUsuario', $userId)
            ->sum('valor');

        $contasAtrasadas = ContaReceber::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->where('data_pagamento', '<', Carbon::now())
            ->sum('valor');

        $dinheiroNaRuaAtrasado = $emprestimosAtrasados + $contasAtrasadas;

        // Juros Mensais a Receber (Atrasados)
        $jurosMensaisAtrasados = Emprestimo::where('status', 'atrasado')
            ->where('idUsuario', $userId)
            ->sum(DB::raw('valor * (juros / 100)')); // Juros mensais para atrasados

        // Contagem de Atrasados (Empréstimos + Contas a Receber)
        $emprestimosAtrasadosCount = Emprestimo::where('status', 'atrasado')
            ->where('idUsuario', $userId)
            ->count();

        $contasAtrasadasCount = ContaReceber::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->where('data_pagamento', '<', Carbon::now())
            ->count();

        $atrasados = $emprestimosAtrasadosCount + $contasAtrasadasCount;

        // Contas a receber (da tabela conta_recebers)
        $contasAReceber = ContaReceber::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->sum('valor');

        // Contas a pagar (da tabela conta_pagars)
        $contasAPagar = ContaPagar::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->sum('valor');

        // Dados para o gráfico de Evolução de Empréstimos
        $evolucaoEmprestimos = $this->getEvolucaoEmprestimos($userId);

        // Dados para o gráfico de Juros Mensais
        $jurosMensais = $this->getJurosMensais($userId);

        // Contagem de empréstimos por status
        $emprestimosPendentes = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->count();
        $emprestimosPagos = Emprestimo::where('status', 'pago')
            ->where('idUsuario', $userId)
            ->count();

        return view('dashboard', compact(
            'dinheiroNaRuaNormal',
            'jurosMensaisNormais',
            'dinheiroNaRuaAtrasado',
            'jurosMensaisAtrasados',
            'atrasados',
            'contasAReceber',
            'contasAPagar',
            'evolucaoEmprestimos',
            'jurosMensais',
            'emprestimosPendentes',
            'emprestimosPagos'
        ));
    }

    private function getEvolucaoEmprestimos($userId)
    {
        $ultimos6Meses = collect();
        for ($i = 5; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $ultimos6Meses->push($data->format('M/Y'));
        }

        $valores = Emprestimo::select(
            DB::raw('DATE_FORMAT(dataPagamento, "%Y-%m") as mes'),
            DB::raw('SUM(valor) as total')
        )
            ->where('dataPagamento', '>=', Carbon::now()->subMonths(5))
            ->where('idUsuario', $userId)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $data = [];
        foreach ($ultimos6Meses as $mes) {
            $data[] = $valores[Carbon::createFromFormat('M/Y', $mes)->format('Y-m')] ?? 0;
        }

        return [
            'labels' => $ultimos6Meses->toArray(),
            'data' => $data
        ];
    }

    private function getJurosMensais($userId)
    {
        $ultimos6Meses = collect();
        for ($i = 5; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $ultimos6Meses->push($data->format('M/Y'));
        }

        $juros = Emprestimo::select(
            DB::raw('DATE_FORMAT(dataPagamento, "%Y-%m") as mes'),
            DB::raw('SUM(valor_jurosdiarios * meses) as total_juros')
        )
            ->where('dataPagamento', '>=', Carbon::now()->subMonths(5))
            ->where('idUsuario', $userId)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total_juros', 'mes')
            ->toArray();

        $data = [];
        foreach ($ultimos6Meses as $mes) {
            $data[] = $juros[Carbon::createFromFormat('M/Y', $mes)->format('Y-m')] ?? 0;
        }

        return [
            'labels' => $ultimos6Meses->toArray(),
            'data' => $data
        ];
    }
}



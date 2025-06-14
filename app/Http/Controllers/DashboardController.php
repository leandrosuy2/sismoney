<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->idUsuario;
        Log::info('Iniciando cálculos do dashboard', ['user_id' => $userId]);

        // Atualiza status dos empréstimos para pago se a data de pagamento passou
        try {
            Log::info('Iniciando atualização de status para pago', [
                'user_id' => $userId,
                'data_atual' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            $emprestimos = Emprestimo::where('status', 'pendente')
                ->where('idUsuario', $userId)
                ->where('dataPagamento', '<', Carbon::now())
                ->get();

            Log::info('Empréstimos encontrados para atualização', [
                'quantidade' => $emprestimos->count(),
                'ids' => $emprestimos->pluck('id')->toArray()
            ]);

            foreach ($emprestimos as $emprestimo) {
                Log::info('Tentando atualizar empréstimo', [
                    'id' => $emprestimo->id,
                    'status_atual' => $emprestimo->status,
                    'data_pagamento' => $emprestimo->dataPagamento
                ]);

                DB::statement("UPDATE emprestimos SET status = 'pago' WHERE id = ?", [$emprestimo->id]);

                Log::info('Empréstimo atualizado com sucesso', [
                    'id' => $emprestimo->id,
                    'novo_status' => 'pago'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status dos empréstimos', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Dinheiro na Rua Normal (todos os empréstimos pendentes)
        $emprestimosNormais = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->get();

        Log::info('Empréstimos normais encontrados:', [
            'quantidade' => $emprestimosNormais->count(),
            'ids' => $emprestimosNormais->pluck('id')->toArray(),
            'valores' => $emprestimosNormais->pluck('valor')->toArray(),
            'datas' => $emprestimosNormais->pluck('dataPagamento')->toArray()
        ]);

        $dinheiroNaRuaNormal = $emprestimosNormais->sum('valor');
        Log::info('Dinheiro na Rua Normal calculado:', ['valor' => $dinheiroNaRuaNormal]);

        // Juros Mensais a Receber (Normais)
        $jurosMensaisNormais = $emprestimosNormais->sum(function($emprestimo) {
            return $emprestimo->valor_jurosdiarios * 30;
        });
        Log::info('Juros Mensais Normais calculados:', ['valor' => $jurosMensaisNormais]);

        // Dinheiro na Rua Atrasado (empréstimos pendentes com data futura)
        $query = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->whereDate('dataPagamento', '>=', Carbon::today());

        Log::info('Query SQL para empréstimos atrasados:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'data_atual' => Carbon::today()->toDateString()
        ]);

        $emprestimosAtrasados = $query->get();

        Log::info('Empréstimos atrasados encontrados:', [
            'quantidade' => $emprestimosAtrasados->count(),
            'ids' => $emprestimosAtrasados->pluck('id')->toArray(),
            'valores' => $emprestimosAtrasados->pluck('valor')->toArray(),
            'datas' => $emprestimosAtrasados->pluck('dataPagamento')->toArray()
        ]);

        $dinheiroNaRuaAtrasado = $emprestimosAtrasados->sum('valor');
        Log::info('Dinheiro na Rua Atrasado calculado:', ['valor' => $dinheiroNaRuaAtrasado]);

        // Juros Mensais a Receber (Atrasados)
        $jurosMensaisAtrasados = $emprestimosAtrasados->sum(function($emprestimo) {
            return $emprestimo->valor_jurosdiarios * 30;
        });
        Log::info('Juros Mensais Atrasados calculados:', ['valor' => $jurosMensaisAtrasados]);

        // Contagem de Atrasados
        $atrasados = $emprestimosAtrasados->count();
        Log::info('Quantidade de atrasados:', ['quantidade' => $atrasados]);

        // Contas a receber
        $contasAReceber = ContaReceber::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->get();

        Log::info('Contas a receber encontradas:', [
            'quantidade' => $contasAReceber->count(),
            'ids' => $contasAReceber->pluck('id')->toArray(),
            'valores' => $contasAReceber->pluck('valor')->toArray()
        ]);

        $contasAReceber = $contasAReceber->sum('valor');
        Log::info('Total de contas a receber:', ['valor' => $contasAReceber]);

        // Contas a pagar
        $contasAPagar = ContaPagar::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->get();

        Log::info('Contas a pagar encontradas:', [
            'quantidade' => $contasAPagar->count(),
            'ids' => $contasAPagar->pluck('id')->toArray(),
            'valores' => $contasAPagar->pluck('valor')->toArray()
        ]);

        $contasAPagar = $contasAPagar->sum('valor');
        Log::info('Total de contas a pagar:', ['valor' => $contasAPagar]);

        // Contagem de empréstimos por status
        $emprestimosPendentes = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->count();
        Log::info('Quantidade de empréstimos pendentes:', ['quantidade' => $emprestimosPendentes]);

        $emprestimosPagos = Emprestimo::where('status', 'pago')
            ->where('idUsuario', $userId)
            ->count();
        Log::info('Quantidade de empréstimos pagos:', ['quantidade' => $emprestimosPagos]);

        // Dados para o gráfico de Evolução de Empréstimos
        $evolucaoEmprestimos = $this->getEvolucaoEmprestimos($userId);
        Log::info('Dados para gráfico de evolução:', $evolucaoEmprestimos);

        // Dados para o gráfico de Juros Mensais
        $jurosMensais = $this->getJurosMensais($userId);
        Log::info('Dados para gráfico de juros:', $jurosMensais);

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




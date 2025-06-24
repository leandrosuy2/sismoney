<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->idUsuario;
        Log::info('Iniciando cálculos do dashboard', ['user_id' => $userId]);

        // Atualiza status dos empréstimos para pago se a data de pagamento passou
        try {
            Log::info('Iniciando atualização de status para pago', [
                'user_id' => $userId,
                'data_atual' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            // Verificar empréstimos pendentes com data passada ANTES da atualização
            $emprestimosParaAtualizar = Emprestimo::where('status', 'pendente')
                ->where('idUsuario', $userId)
                ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
                ->where('dataPagamento', '!=', null) // Excluir datas nulas
                ->where('dataPagamento', '<', Carbon::now())
                ->where('updated_at', '<', Carbon::now()->subHours(24)) // Não alterar empréstimos editados nas últimas 24h
                ->get();

            Log::info('Empréstimos pendentes com data passada (antes da atualização):', [
                'quantidade' => $emprestimosParaAtualizar->count(),
                'ids' => $emprestimosParaAtualizar->pluck('id')->toArray(),
                'datas' => $emprestimosParaAtualizar->pluck('dataPagamento')->toArray(),
                'valores' => $emprestimosParaAtualizar->pluck('valor')->toArray()
            ]);

            $emprestimos = Emprestimo::where('status', 'pendente')
                ->where('idUsuario', $userId)
                ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
                ->where('dataPagamento', '!=', null) // Excluir datas nulas
                ->where('dataPagamento', '<', Carbon::now())
                ->where('updated_at', '<', Carbon::now()->subHours(24)) // Não alterar empréstimos editados nas últimas 24h
                ->get();

            Log::info('Empréstimos encontrados para atualização', [
                'quantidade' => $emprestimos->count(),
                'ids' => $emprestimos->pluck('id')->toArray()
            ]);

            foreach ($emprestimos as $emprestimo) {
                Log::info('Tentando atualizar empréstimo', [
                    'id' => $emprestimo->id,
                    'status_atual' => $emprestimo->status,
                    'data_pagamento' => $emprestimo->dataPagamento,
                    'valor' => $emprestimo->valor,
                    'ultima_atualizacao' => $emprestimo->updated_at
                ]);

                // Não altera automaticamente se o valor for 0.00 (pode ser parcialmente pago)
                if ($emprestimo->valor > 0) {
                    DB::statement("UPDATE emprestimos SET status = 'pago' WHERE id = ?", [$emprestimo->id]);

                    Log::info('Empréstimo atualizado com sucesso', [
                        'id' => $emprestimo->id,
                        'novo_status' => 'pago'
                    ]);
                } else {
                    Log::info('Empréstimo com valor 0.00 - não alterando status automaticamente', [
                        'id' => $emprestimo->id,
                        'valor' => $emprestimo->valor,
                        'status_atual' => $emprestimo->status
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status dos empréstimos', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // TOTAL DE TODOS OS EMPRÉSTIMOS (pendentes e pagos) - excluindo datas inválidas
        $todosEmprestimos = Emprestimo::where('idUsuario', $userId)
            ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
            ->where('dataPagamento', '!=', null) // Excluir datas nulas
            ->get();

        Log::info('Todos os empréstimos encontrados:', [
            'quantidade' => $todosEmprestimos->count(),
            'ids' => $todosEmprestimos->pluck('id')->toArray(),
            'valores' => $todosEmprestimos->pluck('valor')->toArray(),
            'status' => $todosEmprestimos->pluck('status')->toArray()
        ]);

        // TOTAL EMPRESTADO (todos os empréstimos - pendentes e pagos)
        $totalEmprestado = $todosEmprestimos->sum('valor');
        Log::info('Total emprestado calculado:', ['valor' => $totalEmprestado]);

        // TOTAL DE JUROS A RECEBER (todos os empréstimos)
        $totalJurosReceber = $todosEmprestimos->sum(function($emprestimo) {
            return $emprestimo->valor * ($emprestimo->juros / 100);
        });
        Log::info('Total de juros a receber calculado:', ['valor' => $totalJurosReceber]);

        // TOTAL A RECEBER (principal + juros)
        $totalAReceber = $totalEmprestado + $totalJurosReceber;
        Log::info('Total a receber calculado:', ['valor' => $totalAReceber]);

        // Dinheiro na Rua Normal (TOTAL de todos os empréstimos pendentes) - excluindo datas inválidas
        $emprestimosPendentes = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
            ->where('dataPagamento', '!=', null) // Excluir datas nulas
            ->get();

        Log::info('Empréstimos pendentes encontrados:', [
            'quantidade' => $emprestimosPendentes->count(),
            'ids' => $emprestimosPendentes->pluck('id')->toArray(),
            'valores' => $emprestimosPendentes->pluck('valor')->toArray(),
            'datas' => $emprestimosPendentes->pluck('dataPagamento')->toArray()
        ]);

        $dinheiroNaRuaNormal = $emprestimosPendentes->sum('valor');
        Log::info('Dinheiro na Rua Normal calculado:', ['valor' => $dinheiroNaRuaNormal]);

        // Juros Mensais a Receber (TOTAL de juros de todos os empréstimos pendentes)
        $jurosMensaisNormais = $emprestimosPendentes->sum(function($emprestimo) {
            return $emprestimo->valor * ($emprestimo->juros / 100);
        });
        Log::info('Juros Mensais Normais calculados:', ['valor' => $jurosMensaisNormais]);

        // Dinheiro na Rua Atrasado (empréstimos com data passada, independente do status)
        $emprestimosAtrasados = Emprestimo::where('idUsuario', $userId)
            ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
            ->where('dataPagamento', '!=', null) // Excluir datas nulas
            ->whereDate('dataPagamento', '<=', Carbon::today()) // Mudança: <= em vez de < para incluir hoje
            ->get();

        Log::info('Empréstimos atrasados encontrados:', [
            'quantidade' => $emprestimosAtrasados->count(),
            'ids' => $emprestimosAtrasados->pluck('id')->toArray(),
            'valores' => $emprestimosAtrasados->pluck('valor')->toArray(),
            'datas' => $emprestimosAtrasados->pluck('dataPagamento')->toArray(),
            'status' => $emprestimosAtrasados->pluck('status')->toArray()
        ]);

        // LOG DETALHADO PARA DEBUG - Verificar TODOS os empréstimos do usuário
        $todosEmprestimosUsuario = Emprestimo::where('idUsuario', $userId)->get();
        Log::info('TODOS os empréstimos do usuário:', [
            'user_id' => $userId,
            'quantidade_total' => $todosEmprestimosUsuario->count(),
            'data_atual' => Carbon::today()->format('Y-m-d')
        ]);

        // Verificar cada empréstimo individualmente
        foreach ($todosEmprestimosUsuario as $emprestimo) {
            $dataPagamento = $emprestimo->dataPagamento;
            $isDataValida = !empty($dataPagamento) && $dataPagamento != '0000-00-00';
            $isAtrasado = false;

            if ($isDataValida) {
                try {
                    $dataPagamentoObj = Carbon::parse($dataPagamento);
                    $isAtrasado = $dataPagamentoObj->startOfDay()->lt(Carbon::today());
                } catch (\Exception $e) {
                    $isAtrasado = false;
                }
            }

            Log::info('Verificação individual do empréstimo:', [
                'id' => $emprestimo->id,
                'nome' => $emprestimo->nome,
                'data_pagamento' => $dataPagamento,
                'data_valida' => $isDataValida,
                'is_atrasado' => $isAtrasado,
                'status' => $emprestimo->status,
                'valor' => $emprestimo->valor
            ]);
        }

        // Verificar se há algum empréstimo que deveria estar na contagem mas não está
        $emprestimosComDataPassada = $todosEmprestimosUsuario->filter(function($emprestimo) {
            if (empty($emprestimo->dataPagamento) || $emprestimo->dataPagamento == '0000-00-00') {
                return false;
            }
            try {
                return Carbon::parse($emprestimo->dataPagamento)->startOfDay()->lte(Carbon::today());
            } catch (\Exception $e) {
                return false;
            }
        });

        Log::info('Empréstimos com data passada (filtro manual):', [
            'quantidade' => $emprestimosComDataPassada->count(),
            'ids' => $emprestimosComDataPassada->pluck('id')->toArray()
        ]);

        // VERIFICAÇÃO ESPECIAL - Verificar se há empréstimos com data igual a hoje
        $emprestimosComDataHoje = $todosEmprestimosUsuario->filter(function($emprestimo) {
            if (empty($emprestimo->dataPagamento) || $emprestimo->dataPagamento == '0000-00-00') {
                return false;
            }
            try {
                return Carbon::parse($emprestimo->dataPagamento)->startOfDay()->eq(Carbon::today());
            } catch (\Exception $e) {
                return false;
            }
        });

        Log::info('Empréstimos com data de hoje (deveriam estar atrasados?):', [
            'quantidade' => $emprestimosComDataHoje->count(),
            'emprestimos' => $emprestimosComDataHoje->map(function($emprestimo) {
                return [
                    'id' => $emprestimo->id,
                    'nome' => $emprestimo->nome,
                    'data_pagamento' => $emprestimo->dataPagamento,
                    'status' => $emprestimo->status,
                    'valor' => $emprestimo->valor
                ];
            })->toArray()
        ]);

        // VERIFICAÇÃO ESPECIAL - Verificar se há empréstimos com data de ontem
        $emprestimosComDataOntem = $todosEmprestimosUsuario->filter(function($emprestimo) {
            if (empty($emprestimo->dataPagamento) || $emprestimo->dataPagamento == '0000-00-00') {
                return false;
            }
            try {
                return Carbon::parse($emprestimo->dataPagamento)->startOfDay()->eq(Carbon::yesterday());
            } catch (\Exception $e) {
                return false;
            }
        });

        Log::info('Empréstimos com data de ontem:', [
            'quantidade' => $emprestimosComDataOntem->count(),
            'emprestimos' => $emprestimosComDataOntem->map(function($emprestimo) {
                return [
                    'id' => $emprestimo->id,
                    'nome' => $emprestimo->nome,
                    'data_pagamento' => $emprestimo->dataPagamento,
                    'status' => $emprestimo->status,
                    'valor' => $emprestimo->valor
                ];
            })->toArray()
        ]);

        // Verificar diferenças
        $idsAtrasados = $emprestimosAtrasados->pluck('id')->toArray();
        $idsComDataPassada = $emprestimosComDataPassada->pluck('id')->toArray();

        $diferencas = array_diff($idsComDataPassada, $idsAtrasados);
        if (!empty($diferencas)) {
            Log::warning('DIFERENÇA ENCONTRADA! IDs que têm data passada mas não estão na contagem:', [
                'ids_diferenca' => $diferencas
            ]);

            $emprestimosDiferenca = $todosEmprestimosUsuario->whereIn('id', $diferencas);
            Log::info('Detalhes dos empréstimos com diferença:', [
                'emprestimos' => $emprestimosDiferenca->map(function($emprestimo) {
                    return [
                        'id' => $emprestimo->id,
                        'nome' => $emprestimo->nome,
                        'data_pagamento' => $emprestimo->dataPagamento,
                        'status' => $emprestimo->status,
                        'valor' => $emprestimo->valor
                    ];
                })->toArray()
            ]);
        }

        // Verificar TODOS os empréstimos pendentes para debug
        $todosPendentes = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->get();

        Log::info('Todos os empréstimos pendentes (para debug):', [
            'quantidade' => $todosPendentes->count(),
            'ids' => $todosPendentes->pluck('id')->toArray(),
            'valores' => $todosPendentes->pluck('valor')->toArray(),
            'datas' => $todosPendentes->pluck('dataPagamento')->toArray(),
            'data_atual' => Carbon::today()->format('Y-m-d')
        ]);

        $dinheiroNaRuaAtrasado = $emprestimosAtrasados->sum('valor');
        Log::info('Dinheiro na Rua Atrasado calculado:', ['valor' => $dinheiroNaRuaAtrasado]);

        // Juros Mensais a Receber (Atrasados) - usando valor * (juros / 100)
        $jurosMensaisAtrasados = $emprestimosAtrasados->sum(function($emprestimo) {
            return $emprestimo->valor * ($emprestimo->juros / 100);
        });
        Log::info('Juros Mensais Atrasados calculados:', ['valor' => $jurosMensaisAtrasados]);

        // Contagem de Atrasados
        $atrasados = $emprestimosAtrasados->count();
        Log::info('Quantidade de atrasados:', ['quantidade' => $atrasados]);

        // Contas a receber - removendo filtro de idUsuario pois não existe na tabela
        $contasAReceber = ContaReceber::where('status', 'pendente')->get();

        Log::info('Contas a receber encontradas:', [
            'quantidade' => $contasAReceber->count(),
            'ids' => $contasAReceber->pluck('id')->toArray(),
            'valores' => $contasAReceber->pluck('valor')->toArray()
        ]);

        $contasAReceber = $contasAReceber->sum('valor');
        Log::info('Total de contas a receber:', ['valor' => $contasAReceber]);

        // Contas a pagar - usando filtro de idUsuario pois existe na tabela
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
        $emprestimosPendentesCount = Emprestimo::where('status', 'pendente')
            ->where('idUsuario', $userId)
            ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
            ->where('dataPagamento', '!=', null) // Excluir datas nulas
            ->count();
        Log::info('Quantidade de empréstimos pendentes:', ['quantidade' => $emprestimosPendentesCount]);

        $emprestimosPagos = Emprestimo::where('status', 'pago')
            ->where('idUsuario', $userId)
            ->where('dataPagamento', '!=', '0000-00-00') // Excluir datas inválidas
            ->where('dataPagamento', '!=', null) // Excluir datas nulas
            ->count();
        Log::info('Quantidade de empréstimos pagos:', ['quantidade' => $emprestimosPagos]);

        // Dados para o gráfico de Evolução de Empréstimos
        $evolucaoEmprestimos = $this->getEvolucaoEmprestimos($userId);
        Log::info('Dados para gráfico de evolução:', $evolucaoEmprestimos);

        // Dados para o gráfico de Juros Mensais
        $jurosMensais = $this->getJurosMensais($userId);
        Log::info('Dados para gráfico de juros:', $jurosMensais);

        // Dados dos empréstimos atrasados para o modal
        $emprestimosAtrasadosDetalhados = $emprestimosAtrasados->map(function($emprestimo) {
            $dataPagamento = Carbon::parse($emprestimo->dataPagamento);
            $diasAtraso = Carbon::today()->diffInDays($dataPagamento);
            $valorJuros = $emprestimo->valor * ($emprestimo->juros / 100);
            return [
                'id' => $emprestimo->id,
                'nome' => $emprestimo->nome,
                'valor' => $emprestimo->valor,
                'juros' => $emprestimo->juros,
                'dataPagamento' => $emprestimo->dataPagamento,
                'status' => $emprestimo->status,
                'diasAtraso' => $diasAtraso,
                'valorJuros' => $valorJuros
            ];
        });

        return view('dashboard', compact(
            'totalEmprestado',
            'totalJurosReceber',
            'totalAReceber',
            'dinheiroNaRuaNormal',
            'jurosMensaisNormais',
            'dinheiroNaRuaAtrasado',
            'jurosMensaisAtrasados',
            'atrasados',
            'contasAReceber',
            'contasAPagar',
            'evolucaoEmprestimos',
            'jurosMensais',
            'emprestimosPendentesCount',
            'emprestimosPagos',
            'emprestimosAtrasadosDetalhados'
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

    public function pdfAtrasados()
    {
        try {
            $userId = Auth::user()->idUsuario;

            $emprestimosAtrasados = Emprestimo::where('idUsuario', $userId)
                ->where('dataPagamento', '!=', '0000-00-00')
                ->where('dataPagamento', '!=', null)
                ->whereDate('dataPagamento', '<=', Carbon::today())
                ->get();

            $emprestimosAtrasadosDetalhados = $emprestimosAtrasados->map(function($emprestimo) {
                $dataPagamento = Carbon::parse($emprestimo->dataPagamento);
                $diasAtraso = Carbon::today()->diffInDays($dataPagamento);
                $valorJuros = $emprestimo->valor * ($emprestimo->juros / 100);
                return [
                    'id' => $emprestimo->id,
                    'nome' => $emprestimo->nome,
                    'valor' => $emprestimo->valor,
                    'juros' => $emprestimo->juros,
                    'dataPagamento' => $emprestimo->dataPagamento,
                    'status' => $emprestimo->status,
                    'diasAtraso' => $diasAtraso,
                    'valorJuros' => $valorJuros
                ];
            });

            $totalValor = $emprestimosAtrasados->sum('valor');
            $totalJuros = $emprestimosAtrasados->sum(function($emprestimo) {
                return $emprestimo->valor * ($emprestimo->juros / 100);
            });

            $pdf = Pdf::loadView('pdf.atrasados', [
                'emprestimos' => $emprestimosAtrasadosDetalhados,
                'totalValor' => $totalValor,
                'totalJuros' => $totalJuros,
                'quantidade' => $emprestimosAtrasados->count(),
                'dataAtual' => Carbon::now()->format('d/m/Y')
            ]);

            return $pdf->download('emprestimos-atrasados-' . Carbon::now()->format('d-m-Y') . '.pdf');

        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF dos atrasados:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()->with('error', 'Erro ao gerar PDF. Tente novamente.');
        }
    }
}




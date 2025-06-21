<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprestimo;
use App\Models\User;
use Barryvdh\DomPDF\Facades\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    public function emprestimos(Request $request)
    {
        $userId = Auth::user()->idUsuario;

        // Buscar nomes distintos da tabela emprestimos APENAS do usuário logado
        $clientes = Emprestimo::where('idUsuario', $userId)
            ->select('nome')
            ->distinct()
            ->orderBy('nome')
            ->pluck('nome');

        $query = Emprestimo::where('idUsuario', $userId);

        if ($request->filled('cliente')) {
            $query->where('nome', $request->cliente);
        }

        $emprestimos = $query->orderBy('id', 'desc')->paginate(10);

        return view('relatorios.emprestimos', compact('clientes', 'emprestimos'));
    }

    public function pdf(Request $request)
    {
        $userId = Auth::user()->idUsuario;

        Log::info('Iniciando geração do PDF de relatório de empréstimos', [
            'user_id' => $userId,
            'request' => $request->all()
        ]);

        try {
            // Buscar nomes distintos da tabela emprestimos APENAS do usuário logado
            $clientes = Emprestimo::where('idUsuario', $userId)
                ->select('nome')
                ->distinct()
                ->orderBy('nome')
                ->pluck('nome');

            $query = Emprestimo::where('idUsuario', $userId);

            if ($request->filled('cliente')) {
                $query->where('nome', $request->cliente);
            }

            $emprestimos = $query->orderBy('id', 'desc')->get();

            // Exemplo de busca de logs (ajuste conforme sua estrutura real)
            $logs = collect([
                // ['data' => '2025-07-14', 'acao' => 'NOVO EMPRÉSTIMO CRIADO', 'valor' => 150.00, 'juros' => 20.00],
            ]);

            $totalEmprestimos = $emprestimos->sum('valor');
            $totalReceber = $emprestimos->sum(function($e) {
                return $e->valor * ($e->juros / 100);
            });

            Log::info('Dados para PDF', [
                'user_id' => $userId,
                'totalEmprestimos' => $totalEmprestimos,
                'totalReceber' => $totalReceber,
                'qtd_emprestimos' => $emprestimos->count(),
            ]);

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('relatorios.emprestimos_pdf', compact('clientes', 'emprestimos', 'logs', 'totalEmprestimos', 'totalReceber'));
            Log::info('PDF gerado com sucesso');
            return $pdf->download('relatorio-emprestimos.pdf');
        } catch (\Throwable $e) {
            Log::error('Erro ao gerar PDF do relatório de empréstimos', [
                'user_id' => $userId,
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Erro ao gerar PDF: ' . $e->getMessage()], 500);
        }
    }
}

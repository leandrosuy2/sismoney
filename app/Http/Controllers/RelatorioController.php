<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprestimo;
use App\Models\User;
use Barryvdh\DomPDF\Facades\Pdf;
use Illuminate\Support\Facades\Log;

class RelatorioController extends Controller
{
    public function emprestimos(Request $request)
    {
        $clientes = User::select('idUsuario', 'usuario', 'email')->get();
        $query = Emprestimo::query();
        if ($request->filled('cliente')) {
            $query->where('idUsuario', $request->cliente);
        }
        $emprestimos = $query->orderBy('id', 'desc')->get();
        return view('relatorios.emprestimos', compact('clientes', 'emprestimos'));
    }

    public function pdf(Request $request)
    {
        Log::info('Iniciando geração do PDF de relatório de empréstimos', ['request' => $request->all()]);
        try {
            $clientes = User::select('idUsuario', 'usuario', 'email')->get();
            $query = Emprestimo::query();
            if ($request->filled('cliente')) {
                $query->where('idUsuario', $request->cliente);
            }
            $emprestimos = $query->orderBy('id', 'desc')->get();

            // Exemplo de busca de logs (ajuste conforme sua estrutura real)
            $logs = collect([
                // ['data' => '2025-07-14', 'acao' => 'NOVO EMPRÉSTIMO CRIADO', 'valor' => 150.00, 'juros' => 20.00],
            ]);

            $totalEmprestimos = $emprestimos->sum('valor');
            $totalReceber = $emprestimos->sum(function($e) { return $e->valor * ($e->juros / 100); });

            Log::info('Dados para PDF', [
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
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Erro ao gerar PDF: ' . $e->getMessage()], 500);
        }
    }
}

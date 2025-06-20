<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprestimo;
use Illuminate\Support\Facades\Log;

class PainelEmprestimoUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $emprestimos = collect();
        $input = $request->input('cpf_telefone');
        Log::info('[PainelEmprestimoUsuario] Acessando painel', ['cpf_telefone' => $input]);

        if ($input) {
            $inputNumerico = preg_replace('/\D/', '', $input);
            Log::info('[PainelEmprestimoUsuario] Iniciando busca', ['cpf_telefone' => $input, 'input_numerico' => $inputNumerico]);
            $replaceSql = 'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(%s, ".", ""), "-", ""), "/", ""), " ", ""), "(", ""), ")", "")';
            $cpfSql = sprintf($replaceSql, 'cpf');
            $telSql = sprintf($replaceSql, 'telefone');
            $emprestimos = Emprestimo::whereRaw("$cpfSql = ?", [$inputNumerico])
                ->orWhereRaw("$telSql = ?", [$inputNumerico])
                ->orderBy('id', 'desc')
                ->get();
            Log::info('[PainelEmprestimoUsuario] Resultado da busca', [
                'cpf_telefone' => $input,
                'input_numerico' => $inputNumerico,
                'quantidade' => $emprestimos->count(),
                'ids' => $emprestimos->pluck('id')->toArray()
            ]);
        }

        return view('painel_emprestimo_usuario', compact('emprestimos', 'input'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmprestimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emprestimos = Emprestimo::where('idUsuario', auth()->user()->idUsuario)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('emprestimos.index', compact('emprestimos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('emprestimos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Iniciando criação de empréstimo');
        Log::info('Dados recebidos:', $request->all());

        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'cpf' => 'required|string|max:20',
                'valor' => 'required|numeric|min:0',
                'juros' => 'required|numeric|min:0',
                'valor_jurosdiarios' => 'required|numeric|min:0',
                'dataPagamento' => 'required|date',
                'telefone' => 'required|string|max:150',
                'status' => 'nullable|in:pendente,pago,parcela abatida,'
            ]);

            Log::info('Validação passou com sucesso');

            $emprestimo = new Emprestimo();
            $emprestimo->nome = $request->nome;
            $emprestimo->cpf = $request->cpf;
            $emprestimo->valor = $request->valor;
            $emprestimo->juros = $request->juros;
            $emprestimo->valor_jurosdiarios = $request->valor_jurosdiarios;
            $emprestimo->dataPagamento = $request->dataPagamento;
            $emprestimo->telefone = $request->telefone;
            $emprestimo->meses = 0;
            $emprestimo->status = $request->status;
            $emprestimo->idUsuario = auth()->user()->idUsuario;

            Log::info('Modelo criado:', $emprestimo->toArray());

            $emprestimo->save();
            Log::info('Empréstimo salvo com sucesso. ID: ' . $emprestimo->id);

            return redirect()->route('emprestimos.index')
                ->with('success', 'Empréstimo criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar empréstimo: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar empréstimo: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emprestimo $emprestimo)
    {
        if ($emprestimo->idUsuario !== auth()->user()->idUsuario) {
            return redirect()->route('emprestimos.index')
                ->with('error', 'Você não tem permissão para editar este empréstimo.');
        }
        return view('emprestimos.edit', compact('emprestimo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emprestimo $emprestimo)
    {
        if ($emprestimo->idUsuario !== auth()->user()->idUsuario) {
            return redirect()->route('emprestimos.index')
                ->with('error', 'Você não tem permissão para atualizar este empréstimo.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:20',
            'valor' => 'required|numeric|min:0',
            'juros' => 'required|numeric|min:0',
            'valor_jurosdiarios' => 'required|numeric|min:0',
            'dataPagamento' => 'required|date',
            'telefone' => 'required|string|max:150',
            'status' => 'nullable|in:pendente,pago,parcela abatida,'
        ]);

        $emprestimo->nome = $request->nome;
        $emprestimo->cpf = $request->cpf;
        $emprestimo->valor = $request->valor;
        $emprestimo->juros = $request->juros;
        $emprestimo->valor_jurosdiarios = $request->valor_jurosdiarios;
        $emprestimo->dataPagamento = $request->dataPagamento;
        $emprestimo->telefone = $request->telefone;
        $emprestimo->meses = 0;
        $emprestimo->status = $request->status;

        $emprestimo->save();

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprestimo $emprestimo)
    {
        if ($emprestimo->idUsuario !== auth()->user()->idUsuario) {
            return redirect()->route('emprestimos.index')
                ->with('error', 'Você não tem permissão para excluir este empréstimo.');
        }

        $emprestimo->delete();

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo excluído com sucesso!');
    }

    /**
     * Processa o pagamento de um empréstimo
     */
    public function pagar(Request $request, Emprestimo $emprestimo)
    {
        Log::info('Iniciando processo de pagamento', [
            'emprestimo_id' => $emprestimo->id,
            'dados_recebidos' => $request->all()
        ]);

        try {
            Log::info('Validando dados do pagamento');

            // Converte o valor do pagamento para o formato correto antes da validação
            $valorPagamento = str_replace(',', '.', $request->valor_pagamento);
            $request->merge(['valor_pagamento' => $valorPagamento]);

            $request->validate([
                'valor_pagamento' => 'required|numeric|min:0',
                'data_pagamento' => 'required|date'
            ]);

            Log::info('Valores do pagamento', [
                'valor_pagamento' => $request->valor_pagamento,
                'valor_emprestimo' => $emprestimo->valor,
                'tipo_valor_pagamento' => gettype($request->valor_pagamento),
                'tipo_valor_emprestimo' => gettype($emprestimo->valor)
            ]);

            // Converte o valor do pagamento para float
            $valorPagamento = (float) $request->valor_pagamento;
            $valorEmprestimo = (float) $emprestimo->valor;

            Log::info('Valores convertidos', [
                'valor_pagamento_convertido' => $valorPagamento,
                'valor_emprestimo_convertido' => $valorEmprestimo
            ]);

            // Verifica se o valor do pagamento é maior que zero
            if ($valorPagamento <= 0) {
                Log::warning('Valor de pagamento inválido', [
                    'valor_pagamento' => $valorPagamento
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'O valor do pagamento deve ser maior que zero.']);
            }

            // Verifica se o valor do pagamento não é maior que o valor do empréstimo
            if ($valorPagamento > $valorEmprestimo) {
                Log::warning('Valor de pagamento maior que o empréstimo', [
                    'valor_pagamento' => $valorPagamento,
                    'valor_emprestimo' => $valorEmprestimo
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'O valor do pagamento não pode ser maior que o valor do empréstimo.']);
            }

            Log::info('Atualizando empréstimo');

            // Calcula o novo valor do empréstimo
            $novoValor = $valorEmprestimo - $valorPagamento;

            // Atualiza o empréstimo
            $emprestimo->update([
                'valor' => $novoValor,
                'status' => $novoValor <= 0 ? 'pago' : 'pendente',
                'dataPagamento' => $request->data_pagamento
            ]);

            Log::info('Empréstimo atualizado com sucesso', [
                'emprestimo_id' => $emprestimo->id,
                'novo_valor' => $novoValor,
                'novo_status' => $novoValor <= 0 ? 'pago' : 'pendente',
                'data_pagamento' => $request->data_pagamento
            ]);

            return redirect()->route('emprestimos.index')
                ->with('success', 'Pagamento realizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento', [
                'emprestimo_id' => $emprestimo->id,
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao processar pagamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Processa o pagamento de juros de um empréstimo
     */
    public function abater(Request $request, Emprestimo $emprestimo)
    {
        Log::info('Iniciando processo de pagamento de juros', [
            'emprestimo_id' => $emprestimo->id
        ]);

        try {
            // Verifica se o empréstimo já está pago
            if ($emprestimo->status === 'pago') {
                Log::warning('Tentativa de pagar juros de empréstimo já pago', [
                    'emprestimo_id' => $emprestimo->id,
                    'status_atual' => $emprestimo->status
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Não é possível pagar juros de um empréstimo já pago.']);
            }

            // Calcula a próxima data de pagamento usando a data atual de pagamento como base
            $dataAtual = \Carbon\Carbon::parse($emprestimo->dataPagamento);
            $proximaData = $dataAtual->copy()->addMonth();

            Log::info('Datas calculadas', [
                'data_atual' => $dataAtual->format('Y-m-d'),
                'proxima_data' => $proximaData->format('Y-m-d')
            ]);

            // Atualiza o empréstimo
            $dadosAtualizacao = [
                'dataPagamento' => $proximaData->format('Y-m-d'),
                'status' => 'parcela abatida'
            ];

            Log::info('Dados para atualização', $dadosAtualizacao);

            $emprestimo->update($dadosAtualizacao);

            Log::info('Juros marcados como pagos com sucesso', [
                'emprestimo_id' => $emprestimo->id,
                'nova_data_pagamento' => $proximaData->format('Y-m-d'),
                'novo_status' => 'parcela abatida'
            ]);

            return redirect()->route('emprestimos.index')
                ->with('success', 'Juros marcados como pagos com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento de juros', [
                'emprestimo_id' => $emprestimo->id,
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao processar pagamento de juros: ' . $e->getMessage()]);
        }
    }
}

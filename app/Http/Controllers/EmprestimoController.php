<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Emprestimo::where('idUsuario', auth()->id());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nome', 'like', "%{$search}%");
        }

        $emprestimos = $query->orderBy('id', 'desc')->paginate(10);
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
        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'juros' => 'required|numeric|min:0',
            'valor_jurosdiarios' => 'required|numeric|min:0',
            'dataPagamento' => 'required|date',
            'telefone' => 'required|string|max:20',
            'status' => 'required|string|in:pendente,pago,atrasado'
        ]);

        $emprestimo = new Emprestimo($request->all());
        $emprestimo->idUsuario = auth()->id();
        $emprestimo->save();

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo criado com sucesso!');
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
        if ($emprestimo->idUsuario !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar este empréstimo.');
        }

        return view('emprestimos.edit', compact('emprestimo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emprestimo $emprestimo)
    {
        if ($emprestimo->idUsuario !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar este empréstimo.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'juros' => 'required|numeric|min:0',
            'valor_jurosdiarios' => 'required|numeric|min:0',
            'dataPagamento' => 'required|date',
            'telefone' => 'required|string|max:20',
            'status' => 'required|string|in:pendente,pago,atrasado'
        ]);

        $emprestimo->update($request->all());

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprestimo $emprestimo)
    {
        if ($emprestimo->idUsuario !== auth()->id()) {
            abort(403, 'Você não tem permissão para excluir este empréstimo.');
        }

        $emprestimo->delete();

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo excluído com sucesso!');
    }
}

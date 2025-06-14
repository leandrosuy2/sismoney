<?php

namespace App\Http\Controllers;

use App\Models\ContaReceber;
use Illuminate\Http\Request;

class ContaReceberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contas = ContaReceber::where('idUsuario', auth()->user()->idUsuario)->get();
        return view('contas-receber.index', compact('contas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contas-receber.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empresa' => 'required',
            'tipo' => 'required',
            'valor' => 'required|numeric',
            'data_pagamento' => 'required|date',
            'status' => 'required'
        ]);

        $data = $request->all();
        $data['idUsuario'] = auth()->user()->idUsuario;

        ContaReceber::create($data);

        return redirect()->route('contas-receber.index')
            ->with('success', 'Conta criada com sucesso.');
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
    public function edit(ContaReceber $contaReceber)
    {
        return view('contas-receber.edit', compact('contaReceber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContaReceber $contaReceber)
    {
        $request->validate([
            'empresa' => 'required',
            'tipo' => 'required',
            'valor' => 'required|numeric',
            'data_pagamento' => 'required|date',
            'status' => 'required'
        ]);

        $contaReceber->update($request->all());

        return redirect()->route('contas-receber.index')
            ->with('success', 'Conta atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContaReceber $contaReceber)
    {
        $contaReceber->delete();

        return redirect()->route('contas-receber.index')
            ->with('success', 'Conta excluída com sucesso.');
    }
}

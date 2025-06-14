<?php

namespace App\Http\Controllers;

use App\Models\ContaPagar;
use Illuminate\Http\Request;

class ContaPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contas = ContaPagar::where('idUsuario', auth()->user()->idUsuario)->get();
        return view('contas-pagar.index', compact('contas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contas-pagar.create');
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

        ContaPagar::create($data);

        return redirect()->route('contas-pagar.index')
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
    public function edit(ContaPagar $contaPagar)
    {
        return view('contas-pagar.edit', compact('contaPagar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContaPagar $contaPagar)
    {
        $request->validate([
            'empresa' => 'required',
            'tipo' => 'required',
            'valor' => 'required|numeric',
            'data_pagamento' => 'required|date',
            'status' => 'required'
        ]);

        $contaPagar->update($request->all());

        return redirect()->route('contas-pagar.index')
            ->with('success', 'Conta atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContaPagar $contaPagar)
    {
        $contaPagar->delete();

        return redirect()->route('contas-pagar.index')
            ->with('success', 'Conta excluída com sucesso.');
    }
}

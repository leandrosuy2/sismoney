<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmprestimos = Emprestimo::count();
        $emprestimosPendentes = Emprestimo::where('status', 'pendente')->count();
        $emprestimosPagos = Emprestimo::where('status', 'pago')->count();
        $emprestimosAtrasados = Emprestimo::where('status', 'atrasado')->count();
        $ultimosEmprestimos = Emprestimo::orderBy('id', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalEmprestimos',
            'emprestimosPendentes',
            'emprestimosPagos',
            'emprestimosAtrasados',
            'ultimosEmprestimos'
        ));
    }
}

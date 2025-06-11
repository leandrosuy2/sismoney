<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmprestimoUsuarioController extends Controller
{
    public function showLoginForm()
    {
        return view('emprestimos.usuario.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'cpfCnpj' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('cpfCnpj', $request->cpfCnpj)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'cpfCnpj' => 'CPF/CNPJ ou senha inválidos.',
            ]);
        }

        Auth::login($user);

        return redirect()->route('emprestimos.usuario.index');
    }

    public function index()
    {
        $emprestimos = Emprestimo::where('idUsuario', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return view('emprestimos.usuario.index', compact('emprestimos'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('emprestimos.usuario.login');
    }
}

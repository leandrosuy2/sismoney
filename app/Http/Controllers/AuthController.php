<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Busca o usuário pelo email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'E-mail não encontrado.',
            ])->onlyInput('email');
        }

        // Verifica se o usuário está ativo
        if (!$user->ativo) {
            return back()->withErrors([
                'email' => 'Usuário inativo.',
            ])->onlyInput('email');
        }

        // Verifica a senha
        if (Hash::check($credentials['password'], $user->senha)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Senha incorreta.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'cpfCnpj' => 'required|string|max:20|unique:users',
            'telefone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpfCnpj' => $request->cpfCnpj,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'status' => 'ativo'
        ]);

        Auth::login($user);

        return redirect()->intended('dashboard');
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}

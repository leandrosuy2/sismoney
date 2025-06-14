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
        Log::info('Iniciando processo de registro');
        Log::info('Dados recebidos:', $request->all());

        try {
            $request->validate([
                'usuario' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'cpfCnpj' => 'required|string|max:20|unique:users',
                'telefone' => 'required|string|max:20',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Log::info('Validação passou com sucesso');

            $userData = [
                'usuario' => $request->usuario,
                'email' => $request->email,
                'cpfCnpj' => $request->cpfCnpj,
                'telefone' => $request->telefone,
                'senha' => Hash::make($request->password),
                'ativo' => true
            ];

            Log::info('Dados preparados para criação:', $userData);

            $user = User::create($userData);
            Log::info('Usuário criado com sucesso. ID: ' . $user->idUsuario);

            return redirect('/')->with('success', 'Usuário registrado com sucesso! Faça login para continuar.');
        } catch (\Exception $e) {
            Log::error('Erro ao registrar usuário: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao registrar usuário: ' . $e->getMessage()]);
        }
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

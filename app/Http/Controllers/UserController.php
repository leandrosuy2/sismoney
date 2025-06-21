<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        $query = User::query();

        // Aplicar filtro de busca se fornecido
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('usuario', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpfCnpj', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('idUsuario', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        Log::info('Iniciando criação de usuário');
        Log::info('Dados recebidos:', $request->all());

        try {
            $request->validate([
                'usuario' => 'required|string|max:150|unique:users,usuario',
                'email' => 'required|email|max:150|unique:users,email',
                'senha' => 'required|string|min:6|max:150',
                'cpfCnpj' => 'required|string|max:255',
                'telefone' => 'required|string|max:150',
                'isAdmin' => 'boolean'
            ]);

            Log::info('Validação passou com sucesso');

            $user = new User();
            $user->usuario = $request->usuario;
            $user->email = $request->email;
            $user->senha = Hash::make($request->senha);
            $user->cpfCnpj = $request->cpfCnpj;
            $user->telefone = $request->telefone;
            $user->ativo = true;
            $user->isAdmin = $request->has('isAdmin') ? 1 : 0;

            Log::info('Modelo criado:', $user->toArray());

            $user->save();
            Log::info('Usuário salvo com sucesso. ID: ' . $user->idUsuario);

            return redirect()->route('users.index')
                ->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        Log::info('Iniciando atualização de usuário', ['user_id' => $user->idUsuario]);

        try {
            $request->validate([
                'usuario' => ['required', 'string', 'max:150', Rule::unique('users', 'usuario')->ignore($user->idUsuario, 'idUsuario')],
                'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->idUsuario, 'idUsuario')],
                'cpfCnpj' => 'required|string|max:255',
                'telefone' => 'required|string|max:150',
                'isAdmin' => 'boolean',
                'ativo' => 'boolean'
            ]);

            Log::info('Validação passou com sucesso');

            $user->usuario = $request->usuario;
            $user->email = $request->email;
            $user->cpfCnpj = $request->cpfCnpj;
            $user->telefone = $request->telefone;
            $user->ativo = $request->has('ativo') ? 1 : 0;
            $user->isAdmin = $request->has('isAdmin') ? 1 : 0;

            // Se uma nova senha foi fornecida, atualiza
            if ($request->filled('senha')) {
                $request->validate([
                    'senha' => 'string|min:6|max:150'
                ]);
                $user->senha = Hash::make($request->senha);
            }

            Log::info('Modelo atualizado:', $user->toArray());

            $user->save();
            Log::info('Usuário atualizado com sucesso. ID: ' . $user->idUsuario);

            return redirect()->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Verifica se o usuário é admin
        if (!Auth::user()->isAdmin) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        // Impede que o usuário delete a si mesmo
        if ($user->idUsuario === Auth::user()->idUsuario) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        Log::info('Excluindo usuário', ['user_id' => $user->idUsuario]);

        try {
            $user->delete();
            Log::info('Usuário excluído com sucesso. ID: ' . $user->idUsuario);

            return redirect()->route('users.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir usuário: ' . $e->getMessage());
            return redirect()->route('users.index')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}

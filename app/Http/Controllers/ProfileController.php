<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        Log::info('Iniciando atualização de perfil', ['user_id' => $user->idUsuario]);

        try {
            $request->validate([
                'usuario' => ['required', 'string', 'max:150', Rule::unique('users', 'usuario')->ignore($user->idUsuario, 'idUsuario')],
                'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->idUsuario, 'idUsuario')],
                'cpfCnpj' => 'required|string|max:255',
                'telefone' => 'required|string|max:150',
            ]);

            Log::info('Validação passou com sucesso');

            $user->usuario = $request->usuario;
            $user->email = $request->email;
            $user->cpfCnpj = $request->cpfCnpj;
            $user->telefone = $request->telefone;

            Log::info('Modelo atualizado:', $user->toArray());

            $user->save();
            Log::info('Perfil atualizado com sucesso. ID: ' . $user->idUsuario);

            return redirect()->route('profile.show')
                ->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar perfil: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar perfil: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for changing password.
     */
    public function changePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        Log::info('Iniciando alteração de senha', ['user_id' => $user->idUsuario]);

        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|max:150|different:current_password',
                'confirm_password' => 'required|string|same:new_password',
            ]);

            // Verifica se a senha atual está correta
            if (!Hash::check($request->current_password, $user->senha)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'A senha atual está incorreta.']);
            }

            Log::info('Validação de senha passou com sucesso');

            $user->senha = Hash::make($request->new_password);
            $user->save();

            Log::info('Senha alterada com sucesso. ID: ' . $user->idUsuario);

            return redirect()->route('profile.show')
                ->with('success', 'Senha alterada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao alterar senha: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao alterar senha: ' . $e->getMessage()]);
        }
    }
}

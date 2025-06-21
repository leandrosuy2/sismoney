@extends('layouts.dashboard')

@section('title', 'Alterar Senha')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-key mr-2 text-indigo-600"></i>
                            Alterar Senha
                        </h2>
                        <a href="{{ route('profile.show') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar ao Perfil
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Card de Informações -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Dicas de Segurança
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Use pelo menos 6 caracteres</li>
                                        <li>Combine letras maiúsculas, minúsculas e números</li>
                                        <li>Evite usar informações pessoais como data de nascimento</li>
                                        <li>Não compartilhe sua senha com outras pessoas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="max-w-md space-y-6">
                            <!-- Senha Atual -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Senha Atual <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="current_password" id="current_password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Digite sua senha atual">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nova Senha -->
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nova Senha <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="new_password" id="new_password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Digite a nova senha">
                                <p class="mt-1 text-sm text-gray-500">Mínimo 6 caracteres</p>
                                @error('new_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmar Nova Senha -->
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmar Nova Senha <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="confirm_password" id="confirm_password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Confirme a nova senha">
                                @error('confirm_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Indicador de Força da Senha -->
                        <div class="max-w-md">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Força da Senha
                            </label>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div id="password-strength" class="bg-gray-300 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <div id="password-feedback" class="mt-2 text-sm text-gray-500"></div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('profile.show') }}"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-key mr-2"></i>
                                Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Verificação de força da senha
document.getElementById('new_password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('password-strength');
    const feedback = document.getElementById('password-feedback');

    let strength = 0;
    let feedbackText = '';

    // Verificar comprimento
    if (password.length >= 6) strength += 25;
    if (password.length >= 8) strength += 25;

    // Verificar complexidade
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;

    // Limitar a 100%
    strength = Math.min(strength, 100);

    // Atualizar barra de força
    strengthBar.style.width = strength + '%';

    // Definir cor e texto baseado na força
    if (strength < 25) {
        strengthBar.className = 'bg-red-500 h-2.5 rounded-full transition-all duration-300';
        feedbackText = 'Senha muito fraca';
    } else if (strength < 50) {
        strengthBar.className = 'bg-orange-500 h-2.5 rounded-full transition-all duration-300';
        feedbackText = 'Senha fraca';
    } else if (strength < 75) {
        strengthBar.className = 'bg-yellow-500 h-2.5 rounded-full transition-all duration-300';
        feedbackText = 'Senha média';
    } else {
        strengthBar.className = 'bg-green-500 h-2.5 rounded-full transition-all duration-300';
        feedbackText = 'Senha forte';
    }

    feedback.textContent = feedbackText;
});

// Verificar se as senhas coincidem
document.getElementById('confirm_password').addEventListener('input', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = e.target.value;

    if (confirmPassword && newPassword !== confirmPassword) {
        e.target.setCustomValidity('As senhas não coincidem');
    } else {
        e.target.setCustomValidity('');
    }
});

// Verificar novamente quando a nova senha mudar
document.getElementById('new_password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value) {
        confirmPassword.dispatchEvent(new Event('input'));
    }
});
</script>
@endsection

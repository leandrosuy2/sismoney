@extends('layouts.dashboard')

@section('title', 'Meu Perfil')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                            Meu Perfil
                        </h2>
                        <div class="flex space-x-3">
                            <a href="{{ route('profile.edit') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-edit mr-2"></i>
                                Editar Perfil
                            </a>
                            <a href="{{ route('profile.change-password') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-key mr-2"></i>
                                Alterar Senha
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Informações Principais -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Pessoais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ID do Usuário</label>
                                <p class="text-sm text-gray-900">{{ $user->idUsuario }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome de Usuário</label>
                                <p class="text-sm text-gray-900">{{ $user->usuario }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <p class="text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CPF/CNPJ</label>
                                <p class="text-sm text-gray-900">{{ $user->cpfCnpj }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                                <p class="text-sm text-gray-900">{{ $user->telefone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data de Criação</label>
                                <p class="text-sm text-gray-900">
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Status e Permissões -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status e Permissões</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status da Conta</label>
                                @if($user->ativo)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Ativa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Inativa
                                    </span>
                                @endif
                                <p class="mt-1 text-sm text-gray-500">
                                    @if($user->ativo)
                                        Sua conta está ativa e você pode acessar todas as funcionalidades.
                                    @else
                                        Sua conta está inativa. Entre em contato com o administrador.
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Acesso</label>
                                @if($user->isAdmin)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-crown mr-2"></i>
                                        Administrador
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-user mr-2"></i>
                                        Usuário Padrão
                                    </span>
                                @endif
                                <p class="mt-1 text-sm text-gray-500">
                                    @if($user->isAdmin)
                                        Você tem acesso administrativo completo ao sistema.
                                    @else
                                        Você tem acesso às funcionalidades básicas do sistema.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas Pessoais -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Minhas Estatísticas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Meus Empréstimos</p>
                                        <p class="text-2xl font-semibold text-gray-900">
                                            {{ \App\Models\Emprestimo::where('idUsuario', $user->idUsuario)->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Minhas Contas a Pagar</p>
                                        <p class="text-2xl font-semibold text-gray-900">
                                            {{ \App\Models\ContaPagar::where('idUsuario', $user->idUsuario)->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                        <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Minhas Contas a Receber</p>
                                        <p class="text-2xl font-semibold text-gray-900">
                                            {{ \App\Models\ContaReceber::where('idUsuario', $user->idUsuario)->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ações Rápidas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center p-4 bg-white rounded-lg border hover:bg-gray-50 transition-colors">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-2">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Editar Perfil</h4>
                                    <p class="text-sm text-gray-500">Atualizar informações pessoais</p>
                                </div>
                            </a>
                            <a href="{{ route('profile.change-password') }}"
                                class="flex items-center p-4 bg-white rounded-lg border hover:bg-gray-50 transition-colors">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-2">
                                    <i class="fas fa-key text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Alterar Senha</h4>
                                    <p class="text-sm text-gray-500">Modificar senha de acesso</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

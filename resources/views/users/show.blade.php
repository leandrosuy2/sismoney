@extends('layouts.dashboard')

@section('title', 'Detalhes do Usuário')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-user mr-2 text-indigo-600"></i>
                            Detalhes do Usuário
                        </h2>
                        <div class="flex space-x-3">
                            <a href="{{ route('users.edit', $user) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Informações Principais -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status do Usuário</label>
                                @if($user->ativo)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Inativo
                                    </span>
                                @endif
                                <p class="mt-1 text-sm text-gray-500">
                                    @if($user->ativo)
                                        O usuário está ativo e pode acessar o sistema.
                                    @else
                                        O usuário está inativo e não pode acessar o sistema.
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Usuário</label>
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
                                        Este usuário tem acesso administrativo completo ao sistema.
                                    @else
                                        Este usuário tem acesso limitado às funcionalidades básicas.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas do Usuário -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas do Usuário</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Empréstimos</p>
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
                                        <p class="text-sm font-medium text-gray-500">Contas a Pagar</p>
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
                                        <p class="text-sm font-medium text-gray-500">Contas a Receber</p>
                                        <p class="text-2xl font-semibold text-gray-900">
                                            {{ \App\Models\ContaReceber::where('idUsuario', $user->idUsuario)->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Voltar à Lista
                        </a>
                        <a href="{{ route('users.edit', $user) }}"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Usuário
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

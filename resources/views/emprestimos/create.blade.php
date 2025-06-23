@extends('layouts.dashboard')

@section('title', 'Novo Empréstimo')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-hand-holding-usd mr-2 text-indigo-600"></i>
                            Novo Empréstimo
                        </h2>
                        <a href="{{ route('emprestimos.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>

                    <form action="{{ route('emprestimos.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Nome -->
                            <div class="space-y-2">
                                <label for="nome" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user mr-1 text-indigo-600"></i>
                                    Nome
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nome" id="nome"
                                           value="{{ old('nome') }}"
                                           placeholder="Digite o nome"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('nome')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- CPF -->
                            <div class="space-y-2">
                                <label for="cpf" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-id-card mr-1 text-indigo-600"></i>
                                    CPF
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="cpf" id="cpf"
                                           value="{{ old('cpf') }}"
                                           placeholder="Digite o CPF"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('cpf')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Valor -->
                            <div class="space-y-2">
                                <label for="valor" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-dollar-sign mr-1 text-indigo-600"></i>
                                    Valor
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-dollar-sign text-gray-400"></i>
                                    </div>
                                    <input type="number" name="valor" id="valor"
                                           value="{{ old('valor') }}"
                                           step="0.01"
                                           placeholder="0,00"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('valor')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Juros -->
                            <div class="space-y-2">
                                <label for="juros" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-percentage mr-1 text-indigo-600"></i>
                                    Juros (%)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-percentage text-gray-400"></i>
                                    </div>
                                    <input type="number" name="juros" id="juros"
                                           value="{{ old('juros') }}"
                                           step="0.01"
                                           placeholder="0,00"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('juros')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Juros Diários -->
                            <div class="space-y-2">
                                <label for="valor_jurosdiarios" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-money-bill-wave mr-1 text-indigo-600"></i>
                                    Juros Diários
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                                    </div>
                                    <input type="number" name="valor_jurosdiarios" id="valor_jurosdiarios"
                                           value="{{ old('valor_jurosdiarios') }}"
                                           step="0.01"
                                           placeholder="0,00"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('valor_jurosdiarios')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Data de Pagamento -->
                            <div class="space-y-2">
                                <label for="dataPagamento" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-calendar mr-1 text-indigo-600"></i>
                                    Data de Pagamento
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input type="date" name="dataPagamento" id="dataPagamento"
                                           value="{{ old('dataPagamento') }}"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('dataPagamento')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Telefone -->
                            <div class="space-y-2">
                                <label for="telefone" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-phone mr-1 text-indigo-600"></i>
                                    Telefone
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="telefone" id="telefone"
                                           value="{{ old('telefone') }}"
                                           placeholder="Digite o telefone"
                                           required
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('telefone')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                                    Status
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-info-circle text-gray-400"></i>
                                    </div>
                                    <select name="status" id="status"
                                            required
                                            class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                        <option value="">Selecione o status</option>
                                        <option value="pendente" {{ old('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="pago" {{ old('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                                        <option value="" {{ old('status') === '' ? 'selected' : '' }}>Status não disponível</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-x-3 pt-8 border-t border-gray-200">
                            <a href="{{ route('emprestimos.index') }}"
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilização do input type="date" */
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }

    /* Estilização do select */
    select {
        background-image: none !important;
    }

    /* Estilização do input type="number" */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
@endpush

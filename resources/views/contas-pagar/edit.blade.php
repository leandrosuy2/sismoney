@extends('layouts.dashboard')

@section('title', 'Editar Conta a Pagar')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-file-invoice-dollar mr-2 text-indigo-600"></i>
                            Editar Conta a Pagar
                        </h2>
                        <a href="{{ route('contas-pagar.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>

                    <form action="{{ route('contas-pagar.update', $contaPagar) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Empresa -->
                            <div class="space-y-2">
                                <label for="empresa" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-building mr-1 text-indigo-600"></i>
                                    Empresa
                                </label>
                                <div class="relative">
                                    <input type="text" name="empresa" id="empresa"
                                           value="{{ old('empresa', $contaPagar->empresa) }}"
                                           placeholder="Digite o nome da empresa"
                                           required
                                           class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('empresa')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipo -->
                            <div class="space-y-2">
                                <label for="tipo" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-tag mr-1 text-indigo-600"></i>
                                    Tipo
                                </label>
                                <div class="relative">
                                    <select name="tipo" id="tipo" required
                                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 appearance-none bg-white">
                                        <option value="">Selecione o tipo...</option>
                                        <option value="Aluguel" {{ old('tipo', $contaPagar->tipo) == 'Aluguel' ? 'selected' : '' }}>
                                            <i class="fas fa-home"></i> Aluguel
                                        </option>
                                        <option value="Energia" {{ old('tipo', $contaPagar->tipo) == 'Energia' ? 'selected' : '' }}>
                                            <i class="fas fa-bolt"></i> Energia
                                        </option>
                                        <option value="Água" {{ old('tipo', $contaPagar->tipo) == 'Água' ? 'selected' : '' }}>
                                            <i class="fas fa-tint"></i> Água
                                        </option>
                                        <option value="Internet" {{ old('tipo', $contaPagar->tipo) == 'Internet' ? 'selected' : '' }}>
                                            <i class="fas fa-wifi"></i> Internet
                                        </option>
                                        <option value="Telefone" {{ old('tipo', $contaPagar->tipo) == 'Telefone' ? 'selected' : '' }}>
                                            <i class="fas fa-phone"></i> Telefone
                                        </option>
                                        <option value="Outros" {{ old('tipo', $contaPagar->tipo) == 'Outros' ? 'selected' : '' }}>
                                            <i class="fas fa-ellipsis-h"></i> Outros
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                    @error('tipo')
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
                                        <span class="text-gray-500">R$</span>
                                    </div>
                                    <input type="number" name="valor" id="valor"
                                           value="{{ old('valor', $contaPagar->valor) }}"
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

                            <!-- Data de Pagamento -->
                            <div class="space-y-2">
                                <label for="data_pagamento" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-calendar-alt mr-1 text-indigo-600"></i>
                                    Data de Pagamento
                                </label>
                                <div class="relative">
                                    <input type="date" name="data_pagamento" id="data_pagamento"
                                           value="{{ old('data_pagamento', $contaPagar->data_pagamento) }}"
                                           required
                                           class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('data_pagamento')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="space-y-2 md:col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                                    Status
                                </label>
                                <div class="relative">
                                    <select name="status" id="status" required
                                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 appearance-none bg-white">
                                        <option value="">Selecione o status...</option>
                                        <option value="pendente" {{ old('status', $contaPagar->status) == 'pendente' ? 'selected' : '' }}>
                                            <i class="fas fa-clock text-yellow-500"></i> Pendente
                                        </option>
                                        <option value="pago" {{ old('status', $contaPagar->status) == 'pago' ? 'selected' : '' }}>
                                            <i class="fas fa-check text-green-500"></i> Pago
                                        </option>
                                        <option value="atrasado" {{ old('status', $contaPagar->status) == 'atrasado' ? 'selected' : '' }}>
                                            <i class="fas fa-exclamation-triangle text-red-500"></i> Atrasado
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
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
                            <a href="{{ route('contas-pagar.index') }}"
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

@extends('layouts.dashboard')

@section('title', 'Empréstimos')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-hand-holding-usd mr-2 text-indigo-600"></i>
                            Empréstimos
                        </h2>
                        <a href="{{ route('emprestimos.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Empréstimo
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Campo de Pesquisa -->
                    <div class="mb-6">
                        <form action="{{ route('emprestimos.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Pesquisar por nome..."
                                           class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                </div>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Pesquisar
                            </button>
                            @if(request('search'))
                                <a href="{{ route('emprestimos.index') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Limpar
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valor
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Juros
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Juros Diários
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        A Receber
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data de Pagamento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Telefone
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($emprestimos as $emprestimo)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ Str::limit($emprestimo->nome, 7, '...') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            R$ {{ number_format($emprestimo->valor, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($emprestimo->juros, 2, ',', '.') }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @php
                                                $dataAtual = \Carbon\Carbon::now();
                                                $dataPagamento = \Carbon\Carbon::parse($emprestimo->dataPagamento);
                                            @endphp
                                            @if($dataAtual->gt($dataPagamento))
                                                @php
                                                    $diasAtraso = (int)$dataPagamento->diffInDays($dataAtual);
                                                    $valorJurosDiario = $emprestimo->valor_jurosdiarios * $diasAtraso;
                                                @endphp
                                                R$ {{ number_format($valorJurosDiario, 2, ',', '.') }} ({{ $diasAtraso }} dias)
                                            @else
                                                Em dia
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            R$ {{ number_format(($emprestimo->valor * $emprestimo->juros) / 100, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $emprestimo->dataPagamento ? \Carbon\Carbon::parse($emprestimo->dataPagamento)->format('d/m/Y') : 'Não definida' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $emprestimo->telefone }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($emprestimo->status == 'pendente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> Pendente
                                                </span>
                                            @elseif($emprestimo->status == 'pago')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Pago
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Atrasado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('emprestimos.edit', $emprestimo) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar Empréstimo">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="abrirModalExclusao({{ $emprestimo->id }}, '{{ $emprestimo->nome }}')" class="text-red-600 hover:text-red-900" title="Excluir Empréstimo">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @if($emprestimo->status !== 'pago')
                                                    <button onclick="abrirModalPagamento({{ $emprestimo->id }}, '{{ number_format($emprestimo->valor, 2, ',', '.') }}', '{{ $emprestimo->dataPagamento }}')" class="text-green-600 hover:text-green-900" title="Pagar Empréstimo">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </button>
                                                    <button onclick="abrirModalAbatimento({{ $emprestimo->id }})" class="text-blue-600 hover:text-blue-900" title="Abater Parcela">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Nenhum empréstimo encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        {{ $emprestimos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais de Pagamento e Abatimento -->
<div id="modalPagamento" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-[500px] shadow-2xl rounded-lg bg-white transform transition-all">
        <div class="absolute top-0 right-0 pt-4 pr-4">
            <button onclick="fecharModalPagamento()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="mt-3">
            <div class="text-center mb-8">
                <i class="fas fa-money-bill-wave text-4xl text-green-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900">Pagar Empréstimo</h3>
                <p class="mt-2 text-sm text-gray-500">Insira os detalhes do pagamento abaixo</p>
            </div>

            <form id="formPagamento" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="valor_pagamento">
                        Valor do Pagamento
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="text" name="valor_pagamento" id="valor_pagamento"
                               class="block w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                               placeholder="0,00"
                               required>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Valor total do empréstimo: <span id="valor_total" class="font-semibold"></span></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="data_pagamento">
                        Data do Pagamento
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-500"></i>
                        </div>
                        <input type="date" name="data_pagamento" id="data_pagamento"
                               class="block w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                               required>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="fecharModalPagamento()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Confirmar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Abatimento -->
<div id="modalAbatimento" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-[500px] shadow-2xl rounded-lg bg-white transform transition-all">
        <div class="absolute top-0 right-0 pt-4 pr-4">
            <button onclick="fecharModalAbatimento()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="mt-3">
            <div class="text-center mb-8">
                <i class="fas fa-hand-holding-usd text-4xl text-blue-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900">Confirmação</h3>
                <p class="mt-2 text-sm text-gray-500">Você tem certeza que deseja marcar o juros do mês como pago?</p>
            </div>

            <form id="formAbatimento" method="POST" class="space-y-6">
                @csrf
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="fecharModalAbatimento()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Sim, marcar como pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div id="modalExclusao" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirmar Exclusão
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Tem certeza que deseja excluir o empréstimo de <span id="nomeEmprestimo" class="font-semibold text-gray-700"></span>?
                            </p>
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Esta ação não poderá ser desfeita!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="formExclusao" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-trash mr-2"></i>
                        Excluir
                    </button>
                </form>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="fecharModalExclusao()">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Funções para formatar valores monetários
    function formatarValorMonetario(valor) {
        // Remove caracteres não numéricos exceto ponto e vírgula
        valor = valor.replace(/[^\d,]/g, '');

        // Garante que só existe uma vírgula
        const partes = valor.split(',');
        if (partes.length > 2) {
            valor = partes[0] + ',' + partes.slice(1).join('');
        }

        // Limita a duas casas decimais
        if (partes.length > 1) {
            valor = partes[0] + ',' + partes[1].slice(0, 2);
        }

        return valor;
    }

    // Aplica a formatação aos inputs monetários
    document.querySelectorAll('input[name="valor_pagamento"], input[name="valor_abatimento"]').forEach(input => {
        // Remove o type="number" para permitir formatação
        input.type = 'text';

        input.addEventListener('input', function(e) {
            let valor = e.target.value;

            // Permite digitar números e vírgula
            if (!/^[\d,]*$/.test(valor)) {
                e.target.value = valor.replace(/[^\d,]/g, '');
                return;
            }

            // Formata o valor
            e.target.value = formatarValorMonetario(valor);
        });

        // Formata o valor quando o campo perde o foco
        input.addEventListener('blur', function(e) {
            let valor = e.target.value;
            if (valor) {
                // Converte para número
                valor = valor.replace(',', '.');
                valor = parseFloat(valor).toFixed(2);
                // Formata para exibição
                valor = valor.replace('.', ',');
                e.target.value = valor;
            }
        });
    });

    // Funções dos modais
    function abrirModalPagamento(emprestimoId, valorRestante, dataPagamento, valorTotal) {
        const modal = document.getElementById('modalPagamento');
        const form = document.getElementById('formPagamento');
        form.action = `/emprestimos/${emprestimoId}/pagar`;
        modal.classList.remove('hidden');
        modal.querySelector('.transform').classList.add('scale-100');
        modal.querySelector('.transform').classList.remove('scale-95');
        document.getElementById('valor_pagamento').value = valorRestante;
        document.getElementById('valor_total').textContent = `R$ ${valorTotal}`;

        // Formata a data para o formato YYYY-MM-DD que o input type="date" espera
        const dataFormatada = new Date(dataPagamento).toISOString().split('T')[0];
        document.getElementById('data_pagamento').value = dataFormatada;
    }

    function fecharModalPagamento() {
        const modal = document.getElementById('modalPagamento');
        modal.querySelector('.transform').classList.add('scale-95');
        modal.querySelector('.transform').classList.remove('scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    function abrirModalAbatimento(emprestimoId) {
        const modal = document.getElementById('modalAbatimento');
        const form = document.getElementById('formAbatimento');
        form.action = `/emprestimos/${emprestimoId}/abater`;
        modal.classList.remove('hidden');
        modal.querySelector('.transform').classList.add('scale-100');
        modal.querySelector('.transform').classList.remove('scale-95');
    }

    function fecharModalAbatimento() {
        const modal = document.getElementById('modalAbatimento');
        modal.querySelector('.transform').classList.add('scale-95');
        modal.querySelector('.transform').classList.remove('scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    // Fechar modais ao clicar fora
    window.onclick = function(event) {
        const modalPagamento = document.getElementById('modalPagamento');
        const modalAbatimento = document.getElementById('modalAbatimento');
        if (event.target == modalPagamento) {
            fecharModalPagamento();
        }
        if (event.target == modalAbatimento) {
            fecharModalAbatimento();
        }
    }

    // Funções do modal de exclusão
    function abrirModalExclusao(emprestimoId, nome) {
        const modal = document.getElementById('modalExclusao');
        const form = document.getElementById('formExclusao');
        const nomeSpan = document.getElementById('nomeEmprestimo');

        // Atualiza o formulário e o nome
        form.action = `/emprestimos/${emprestimoId}`;
        nomeSpan.textContent = nome;

        // Mostra o modal
        modal.classList.remove('hidden');

        // Adiciona animação de entrada
        const modalPanel = modal.querySelector('.inline-block');
        modalPanel.classList.add('animate-modal-enter');
        modalPanel.classList.remove('animate-modal-leave');

        // Previne o scroll do body
        document.body.style.overflow = 'hidden';
    }

    function fecharModalExclusao() {
        const modal = document.getElementById('modalExclusao');
        const modalPanel = modal.querySelector('.inline-block');

        // Adiciona animação de saída
        modalPanel.classList.remove('animate-modal-enter');
        modalPanel.classList.add('animate-modal-leave');

        // Esconde o modal após a animação
        setTimeout(() => {
            modal.classList.add('hidden');
            // Restaura o scroll do body
            document.body.style.overflow = 'auto';
        }, 200);
    }

    // Fecha o modal ao clicar fora
    document.getElementById('modalExclusao').addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalExclusao();
        }
    });

    // Fecha o modal com a tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('modalExclusao').classList.contains('hidden')) {
            fecharModalExclusao();
        }
    });
</script>

<style>
    @keyframes modal-enter {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes modal-leave {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    .animate-modal-enter {
        animation: modal-enter 0.2s ease-out;
    }

    .animate-modal-leave {
        animation: modal-leave 0.2s ease-in;
    }
</style>
@endpush
@endsection

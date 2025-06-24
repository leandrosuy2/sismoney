@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="py-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-tachometer-alt mr-2 text-indigo-600"></i>
                            Dashboard
                        </h2>
                    </div>

                    <!-- Cards Principais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <!-- Total Emprestado -->
                        <div class="bg-green-500 overflow-hidden shadow rounded-lg">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-md p-2">
                                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs font-medium text-white opacity-90 truncate">
                                                Total Emprestado
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-bold text-white">
                                                    R$ {{ number_format($totalEmprestado ?? 0, 0, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dinheiro na Rua Atrasado -->
                        <div class="bg-red-500 overflow-hidden shadow rounded-lg">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-md p-2">
                                        <i class="fas fa-exclamation-circle text-white text-xl"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs font-medium text-white opacity-90 truncate">
                                                Dinheiro Atrasado
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-bold text-white">
                                                    R$ {{ number_format($dinheiroNaRuaAtrasado ?? 0, 0, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Atrasados -->
                        <div class="bg-orange-500 overflow-hidden shadow rounded-lg cursor-pointer hover:bg-orange-600 transition-colors duration-200" onclick="openAtrasadosModal()">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-md p-2">
                                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs font-medium text-white opacity-90 truncate">
                                                Atrasados
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-bold text-white">
                                                    {{ $atrasados ?? 0 }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Secundários -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <!-- Total de Juros a Receber -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                                        <i class="fas fa-percentage text-blue-600 text-lg"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Juros a Receber
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    R$ {{ number_format($totalJurosReceber ?? 0, 0, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Juros Mensais a Receber (Atrasados) -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-orange-500">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-orange-100 rounded-md p-2">
                                        <i class="fas fa-clock text-orange-600 text-lg"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Juros Atrasados
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    R$ {{ number_format($jurosMensaisAtrasados ?? 0, 0, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contas a receber -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-100 rounded-md p-2">
                                        <i class="fas fa-hand-holding-usd text-green-600 text-lg"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Contas a Receber
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    R$ {{ number_format($contasAReceber ?? 0, 0, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Terciários -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <!-- Contas a pagar -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-red-500">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-100 rounded-md p-2">
                                        <i class="fas fa-file-invoice-dollar text-red-600 text-lg"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Contas a Pagar
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    R$ {{ number_format($contasAPagar ?? 0, 0, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Evolução de Empréstimos -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4">
                                <h3 class="text-base font-medium text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
                                    Evolução de Empréstimos
                                </h3>
                                <div style="height: 250px;">
                                    <canvas id="evolucaoEmprestimos"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Juros Mensais -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4">
                                <h3 class="text-base font-medium text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                    Juros Mensais
                                </h3>
                                <div style="height: 250px;">
                                    <canvas id="jurosMensais"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Status dos Empréstimos -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4">
                                <h3 class="text-base font-medium text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                                    Status dos Empréstimos
                                </h3>
                                <div style="height: 250px;">
                                    <canvas id="statusEmprestimos"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Empréstimos Atrasados -->
<div id="atrasadosModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
        <!-- Header do Modal -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-t-lg" style="background: linear-gradient(to right, #f97316, #ef4444);">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Empréstimos Atrasados</h2>
                    <p class="text-orange-100 text-lg">Total de {{ $atrasados }} empréstimos em atraso</p>
                </div>
            </div>
            <button onclick="closeAtrasadosModal()" class="text-white hover:text-orange-200 transition-colors duration-200 bg-white bg-opacity-20 rounded-full p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <i class="fas fa-dollar-sign text-red-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Valor Total Atrasado</p>
                            <p class="text-lg font-bold text-gray-900">R$ {{ number_format($dinheiroNaRuaAtrasado, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <i class="fas fa-percentage text-orange-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Juros Atrasados</p>
                            <p class="text-lg font-bold text-gray-900">R$ {{ number_format($jurosMensaisAtrasados, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-calendar text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Data Atual</p>
                            <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content com Scroll -->
        <div class="flex-1 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Detalhes dos Empréstimos Atrasados</h3>
                    <div class="flex space-x-2">
                        <button onclick="printAtrasados()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-print mr-2"></i>Gerar PDF
                        </button>
                    </div>
                </div>

                <!-- Tabela com Scroll -->
                <div class="overflow-x-auto">
                    <div class="overflow-y-auto max-h-96">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Cliente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-dollar-sign mr-2"></i>Valor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-calendar mr-2"></i>Data Vencimento
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-clock mr-2"></i>Dias Atraso
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-percentage mr-2"></i>Juros
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($emprestimosAtrasadosDetalhados as $emprestimo)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-orange-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $emprestimo['nome'] }}</div>
                                                    <div class="text-sm text-gray-500">ID: {{ $emprestimo['id'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">R$ {{ number_format($emprestimo['valor'], 2, ',', '.') }}</div>
                                            <div class="text-sm text-gray-500">{{ $emprestimo['juros'] }}% juros</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($emprestimo['dataPagamento'])->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $emprestimo['status'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $emprestimo['diasAtraso'] }} dias
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            R$ {{ number_format($emprestimo['valorJuros'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            <i class="fas fa-check-circle text-6xl mb-4"></i>
                                            <div class="text-xl font-medium text-gray-900 mb-2">Nenhum empréstimo atrasado!</div>
                                            <div class="text-gray-600">Todos os empréstimos estão em dia.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer do Modal -->
        <div class="flex justify-end p-6 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <button onclick="closeAtrasadosModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                Fechar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Garantir que o script só rode uma vez
if (!window.chartsInitialized) {
    window.chartsInitialized = true;

    // Gráfico de Evolução de Empréstimos
    new Chart(document.getElementById('evolucaoEmprestimos'), {
        type: 'line',
        data: {
            labels: @json($evolucaoEmprestimos['labels']),
            datasets: [{
                label: 'Valor Total',
                data: @json($evolucaoEmprestimos['data']),
                borderColor: 'rgb(79, 70, 229)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Gráfico de Juros Mensais
    new Chart(document.getElementById('jurosMensais'), {
        type: 'bar',
        data: {
            labels: @json($jurosMensais['labels']),
            datasets: [{
                label: 'Juros',
                data: @json($jurosMensais['data']),
                backgroundColor: 'rgb(59, 130, 246)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Gráfico de Status dos Empréstimos
    new Chart(document.getElementById('statusEmprestimos'), {
        type: 'doughnut',
        data: {
            labels: ['Pendentes', 'Pagos', 'Atrasados'],
            datasets: [{
                data: [
                    {{ $emprestimosPendentesCount ?? 0 }},
                    {{ $emprestimosPagos ?? 0 }},
                    {{ $atrasados ?? 0 }}
                ],
                backgroundColor: [
                    'rgb(234, 179, 8)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// Funções do Modal de Atrasados
function openAtrasadosModal() {
    document.getElementById('atrasadosModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAtrasadosModal() {
    document.getElementById('atrasadosModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function printAtrasados() {
    // Abrir nova janela para gerar PDF
    window.open('/emprestimos/atrasados/pdf', '_blank');
}

// Fechar modal ao clicar fora
document.getElementById('atrasadosModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAtrasadosModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAtrasadosModal();
    }
});
</script>
@endpush
@endsection

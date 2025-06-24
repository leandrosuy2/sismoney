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
                        <div class="bg-orange-500 overflow-hidden shadow rounded-lg">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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

                        <!-- Saldo -->
                        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-purple-500">
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-2">
                                        <i class="fas fa-balance-scale text-purple-600 text-lg"></i>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Saldo
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    R$ {{ number_format(($contasAReceber ?? 0) - ($contasAPagar ?? 0), 0, ',', '.') }}
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
</script>
@endpush
@endsection

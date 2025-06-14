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

                    <!-- Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Dinheiro na Rua Normal -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Dinheiro na Rua Normal
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    R$ {{ number_format($dinheiroNaRuaNormal ?? 0, 2, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Juros Mensais a Receber (Normais) -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <i class="fas fa-percentage text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Juros Mensais a Receber (Normais)
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    R$ {{ number_format($jurosMensaisNormais ?? 0, 2, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dinheiro na Rua Atrasado -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-exclamation-circle text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Dinheiro na Rua Atrasado
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    R$ {{ number_format($dinheiroNaRuaAtrasado ?? 0, 2, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Juros Mensais a Receber (Atrasados) -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                                        <i class="fas fa-clock text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Juros Mensais a Receber (Atrasados)
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    R$ {{ number_format($jurosMensaisAtrasados ?? 0, 2, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Atrasados -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Atrasados
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $atrasados ?? 0 }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contas a receber -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Contas a receber
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    R$ {{ number_format($contasAReceber ?? 0, 2, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contas a pagar -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Contas a pagar
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    R$ {{ number_format($contasAPagar ?? 0, 2, ',', '.') }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Evolução de Empréstimos -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Evolução de Empréstimos
                                </h3>
                                <div style="height: 300px;">
                                    <canvas id="evolucaoEmprestimos"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Juros Mensais -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Juros Mensais
                                </h3>
                                <div style="height: 300px;">
                                    <canvas id="jurosMensais"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Status dos Empréstimos -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Status dos Empréstimos
                                </h3>
                                <div style="height: 300px;">
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
                    {{ $emprestimosPendentes ?? 0 }},
                    {{ $emprestimosPagos ?? 0 }},
                    {{ $emprestimosAtrasados ?? 0 }}
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

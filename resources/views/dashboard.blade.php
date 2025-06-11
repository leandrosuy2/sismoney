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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Total de Empréstimos -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Total de Empréstimos
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $totalEmprestimos ?? 0 }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a href="{{ route('emprestimos.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                        Ver todos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Empréstimos Pendentes -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                        <i class="fas fa-clock text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Empréstimos Pendentes
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $emprestimosPendentes ?? 0 }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a href="{{ route('emprestimos.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                        Ver todos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Empréstimos Pagos -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-check text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Empréstimos Pagos
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $emprestimosPagos ?? 0 }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a href="{{ route('emprestimos.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                        Ver todos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Empréstimos Atrasados -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Empréstimos Atrasados
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $emprestimosAtrasados ?? 0 }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a href="{{ route('emprestimos.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                        Ver todos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Últimos Empréstimos -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-history mr-2 text-indigo-600"></i>
                            Últimos Empréstimos
                        </h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @forelse($ultimosEmprestimos ?? [] as $emprestimo)
                                    <li>
                                        <a href="{{ route('emprestimos.edit', $emprestimo->id) }}" class="block hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                                            {{ $emprestimo->nome }}
                                                        </p>
                                                        <div class="ml-2 flex-shrink-0 flex">
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
                                                        </div>
                                                    </div>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            R$ {{ number_format($emprestimo->valor, 2, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <p class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                            {{ $emprestimo->dataPagamento ? $emprestimo->dataPagamento->format('d/m/Y') : 'Não definido' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                                        Nenhum empréstimo encontrado.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

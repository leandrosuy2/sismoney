<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Consulta de Empréstimos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #e0f7ef 0%, #b2f0c7 100%); }
        .glass {
            background: rgba(255,255,255,0.7);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,0.18);
        }
        .animate-fadein { animation: fadein 0.7s; }
        @keyframes fadein { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: none; } }
    </style>
</head>
<body class="min-h-screen w-full font-sans">
    <!-- Header premium -->
    <header class="w-full bg-gradient-to-r from-green-500 via-green-400 to-green-600 shadow-lg py-5 px-6 flex items-center gap-4 sticky top-0 z-20">
        <i class="fas fa-search-dollar text-3xl text-white drop-shadow"></i>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight drop-shadow">Consultar Empréstimos</h1>
    </header>
    <main class="w-full max-w-5xl mx-auto px-2 sm:px-6 md:px-10 py-10">
        <!-- Formulário glass -->
        <section class="w-full max-w-2xl mx-auto glass p-8 md:p-12 mb-10 animate-fadein">
            <h2 class="text-2xl font-bold text-green-700 mb-3 flex items-center gap-2"><i class="fas fa-user-check"></i> Consulta rápida</h2>
            <p class="text-gray-700 mb-7 text-lg">Digite seu <span class="font-semibold">CPF</span> ou <span class="font-semibold">telefone</span> para consultar seus empréstimos de forma rápida e segura.</p>
            <form method="GET" action="" class="space-y-6">
                <div>
                    <label for="cpf_telefone" class="block text-gray-700 font-medium mb-2">CPF ou Telefone</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-id-card text-green-400"></i>
                        </span>
                        <input type="text" id="cpf_telefone" name="cpf_telefone" class="w-full border border-green-200 rounded-xl px-12 py-4 focus:outline-none focus:ring-2 focus:ring-green-400 text-gray-900 placeholder-gray-400 shadow-sm text-lg bg-white/80" placeholder="Digite o CPF ou telefone" value="{{ old('cpf_telefone', $input ?? '') }}">
                    </div>
                </div>
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-green-700 text-white py-4 rounded-xl font-bold text-xl shadow-lg hover:from-green-600 hover:to-green-800 transition-all duration-200">
                    <i class="fas fa-search"></i>
                    Buscar Empréstimos
                </button>
            </form>
        </section>

        @if(isset($input) && $input !== null)
            <section class="w-full animate-fadein">
                @if($emprestimos->count() > 0)
                    <h2 class="text-2xl font-bold text-green-700 mb-6 text-center flex items-center justify-center gap-2 w-full">
                        <i class="fas fa-list-alt"></i> Resultados encontrados
                    </h2>
                    <!-- Tabela para telas médias e grandes -->
                    <div class="hidden md:block w-full overflow-x-auto rounded-2xl shadow-xl bg-white/90">
                        <table class="min-w-full w-full rounded-2xl text-lg">
                            <thead class="bg-gradient-to-r from-green-200 to-green-100 text-green-900">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">Nome</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">Telefone</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">CPF</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">Valor</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">Juros</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">Data Pagamento</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase border-b">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-green-50">
                                @foreach($emprestimos as $emprestimo)
                                    <tr class="hover:bg-green-50 transition {{ $loop->even ? 'bg-green-50/60' : 'bg-white/80' }}">
                                        <td class="px-6 py-4 text-gray-900 font-semibold">{{ $emprestimo->nome }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ $emprestimo->telefone }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ $emprestimo->cpf }}</td>
                                        <td class="px-6 py-4 text-green-700 font-bold">R$ {{ number_format($emprestimo->valor, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ number_format($emprestimo->juros, 2, ',', '.') }}%</td>
                                        <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($emprestimo->dataPagamento)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusMap = [
                                                    'pendente' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock', 'label' => 'Pendente'],
                                                    'pago' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'check-circle', 'label' => 'Pago'],
                                                    'atrasado' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'exclamation-circle', 'label' => 'Atrasado']
                                                ];
                                                $status = $statusMap[$emprestimo->status] ?? $statusMap['pendente'];
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} shadow-sm" title="{{ $status['label'] }}">
                                                <i class="fas fa-{{ $status['icon'] }}"></i> {{ $status['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Cards para mobile -->
                    <div class="md:hidden flex flex-col gap-6 w-full mt-6">
                        @foreach($emprestimos as $emprestimo)
                            <div class="glass rounded-2xl shadow-xl border border-green-100 p-5 flex flex-col gap-2 w-full animate-fadein">
                                <div class="flex items-center gap-3 mb-2">
                                    <i class="fas fa-user text-green-600 text-xl"></i>
                                    <span class="font-bold text-gray-900 text-lg">{{ $emprestimo->nome }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-base text-gray-700">
                                    <i class="fas fa-phone-alt text-green-400"></i> {{ $emprestimo->telefone }}
                                </div>
                                <div class="flex items-center gap-2 text-base text-gray-700">
                                    <i class="fas fa-id-card text-green-400"></i> {{ $emprestimo->cpf }}
                                </div>
                                <div class="flex items-center gap-2 text-base text-green-700 font-bold">
                                    <i class="fas fa-money-bill-wave"></i> R$ {{ number_format($emprestimo->valor, 2, ',', '.') }}
                                </div>
                                <div class="flex items-center gap-2 text-base text-gray-700">
                                    <i class="fas fa-percent"></i> Juros: {{ number_format($emprestimo->juros, 2, ',', '.') }}%
                                </div>
                                <div class="flex items-center gap-2 text-base text-gray-700">
                                    <i class="fas fa-calendar-alt"></i> Pagamento: {{ \Carbon\Carbon::parse($emprestimo->dataPagamento)->format('d/m/Y') }}
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    @php
                                        $statusMap = [
                                            'pendente' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock', 'label' => 'Pendente'],
                                            'pago' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'check-circle', 'label' => 'Pago'],
                                            'atrasado' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'exclamation-circle', 'label' => 'Atrasado']
                                        ];
                                        $status = $statusMap[$emprestimo->status] ?? $statusMap['pendente'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} shadow-sm" title="{{ $status['label'] }}">
                                        <i class="fas fa-{{ $status['icon'] }}"></i> {{ $status['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center text-gray-600 mt-16 gap-3 animate-fadein">
                        <i class="fas fa-info-circle text-4xl text-yellow-500 mb-2"></i>
                        <p class="text-xl font-semibold">Nenhum empréstimo encontrado para o CPF ou telefone informado.</p>
                    </div>
                @endif
            </section>
        @endif
    </main>
</body>
</html>

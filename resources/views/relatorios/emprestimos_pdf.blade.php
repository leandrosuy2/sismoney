<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Empréstimos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f3f3; }
        .totais { font-weight: bold; }
        .section-title { margin-top: 30px; font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Relatório de Empréstimos</h1>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Valor</th>
                <th>Juros (%)</th>
                <th>A Receber</th>
                <th>Data de Pagamento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emprestimos as $e)
                <tr>
                    <td>{{ $e->nome }}</td>
                    <td>R$ {{ number_format($e->valor, 2, ',', '.') }}</td>
                    <td>{{ number_format($e->juros, 2, ',', '.') }}%</td>
                    <td>R$ {{ number_format($e->valor * ($e->juros / 100), 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($e->dataPagamento)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($e->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="totais">
        Total de Empréstimos: R$ {{ number_format($totalEmprestimos, 2, ',', '.') }}<br>
        Total a Receber: R$ {{ number_format($totalReceber, 2, ',', '.') }}
    </div>

    <div class="section-title">Histórico de Logs</div>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Ação</th>
                <th>Valor</th>
                <th>Juros</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log['data'] ?? '' }}</td>
                    <td>{{ $log['acao'] ?? '' }}</td>
                    <td>{{ isset($log['valor']) ? number_format($log['valor'], 2, ',', '.') : '' }}</td>
                    <td>{{ isset($log['juros']) ? number_format($log['juros'], 2, ',', '.') : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum log encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

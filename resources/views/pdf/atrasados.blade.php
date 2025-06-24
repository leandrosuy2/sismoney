<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Empréstimos Atrasados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e53e3e;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #e53e3e;
            font-size: 24px;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #f7fafc;
            padding: 15px;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #e53e3e;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #e53e3e;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .valor {
            font-weight: bold;
            color: #e53e3e;
        }
        .dias-atraso {
            background-color: #fed7d7;
            color: #c53030;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Empréstimos Atrasados</h1>
        <p>Relatório gerado em: {{ $dataAtual }}</p>
        <p>Total de {{ $quantidade }} empréstimos em atraso</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">R$ {{ number_format($totalValor, 2, ',', '.') }}</div>
            <div class="stat-label">Valor Total Atrasado</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">R$ {{ number_format($totalJuros, 2, ',', '.') }}</div>
            <div class="stat-label">Juros Atrasados</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $quantidade }}</div>
            <div class="stat-label">Quantidade</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Data Vencimento</th>
                <th>Dias Atraso</th>
                <th>Juros</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emprestimos as $emprestimo)
                <tr>
                    <td>
                        <strong>{{ $emprestimo['nome'] }}</strong><br>
                        <small>ID: {{ $emprestimo['id'] }}</small>
                    </td>
                    <td class="valor">
                        R$ {{ number_format($emprestimo['valor'], 2, ',', '.') }}<br>
                        <small>{{ $emprestimo['juros'] }}% juros</small>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($emprestimo['dataPagamento'])->format('d/m/Y') }}<br>
                        <small>{{ $emprestimo['status'] }}</small>
                    </td>
                    <td>
                        <span class="dias-atraso">{{ $emprestimo['diasAtraso'] }} dias</span>
                    </td>
                    <td class="valor">
                        R$ {{ number_format($emprestimo['valorJuros'], 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema SISMoney</p>
        <p>Data de geração: {{ $dataAtual }}</p>
    </div>
</body>
</html>

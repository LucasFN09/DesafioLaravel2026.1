<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Vendas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Histórico de Vendas</h1>
        <p>Vendedor: {{ $user->nome }} | Data: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Comprador</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compras as $compra)
            <tr>
                <td>{{ $compra->data->format('d/m/Y') }}</td>
                <td>{{ $compra->produto->nome }}</td>
                <td>{{ $compra->produto->categoria }}</td>
                <td>{{ $compra->comprador->nome }}</td>
                <td>{{ $compra->quantidade }}</td>
                <td>R$ {{ number_format($compra->valor, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
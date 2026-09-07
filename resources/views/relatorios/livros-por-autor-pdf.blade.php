<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório - Livros por Autor</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #212529;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        .gerado-em {
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
        }
        .autor-row td {
            font-weight: bold;
            background-color: #f1f3f5;
        }
    </style>
</head>
<body>
    <h1>Relatório de Livros por Autor</h1>
    <div class="gerado-em">Gerado em {{ now()->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Editora</th>
                <th>Edição</th>
                <th>Ano</th>
                <th>Valor</th>
                <th>Assuntos</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($livrosPorAutor as $autor => $livros)
                <tr class="autor-row">
                    <td colspan="6">{{ $autor }}</td>
                </tr>
                @foreach ($livros as $livro)
                    <tr>
                        <td>{{ $livro->livro }}</td>
                        <td>{{ $livro->editora }}</td>
                        <td>{{ $livro->edicao }}ª</td>
                        <td>{{ $livro->ano_publicacao }}</td>
                        <td>R$ {{ number_format($livro->valor, 2, ',', '.') }}</td>
                        <td>{{ $livro->assuntos }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6">Nenhum livro cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

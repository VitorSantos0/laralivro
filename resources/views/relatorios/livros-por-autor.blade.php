@extends('layouts.app')

@section('title', 'Livros por Autor')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Livros por Autor</h1>
        <a href="{{ route('relatorios.livros-por-autor.pdf') }}" class="btn btn-outline-danger">
            Exportar PDF
        </a>
    </div>

    @if ($livrosPorAutor->isEmpty())
        <p class="text-muted">Nenhum livro cadastrado ainda.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
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
                    @foreach ($livrosPorAutor as $autor => $livros)
                        <tr class="table-secondary">
                            <th colspan="6">{{ $autor }}</th>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

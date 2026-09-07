@extends('layouts.app')

@section('title', 'Livros')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Livros</h1>
        <a href="{{ route('livros.create') }}" class="btn btn-primary">Novo Livro</a>
    </div>

    @if ($livros->isEmpty())
        <p class="text-muted">Nenhum livro cadastrado ainda.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autores</th>
                        <th>Valor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($livros as $livro)
                        <tr>
                            <td>{{ $livro->titulo }}</td>
                            <td>{{ $livro->autores->pluck('nome')->join(', ') }}</td>
                            <td>R$ {{ number_format($livro->valor, 2, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('livros.edit', $livro) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('livros.destroy', $livro) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este livro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
@extends('layouts.app')

@section('title', 'Autores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Autores</h1>
        <a href="{{ route('autores.create') }}" class="btn btn-primary">Novo Autor</a>
    </div>

    @if ($autores->isEmpty())
        <p class="text-muted">Nenhum autor cadastrado ainda.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($autores as $autor)
                        <tr>
                            <td>{{ $autor->nome }}</td>
                            <td class="text-end">
                                <a href="{{ route('autores.edit', $autor) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('autores.destroy', $autor) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este autor(a)?');">
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
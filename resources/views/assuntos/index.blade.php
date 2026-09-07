@extends('layouts.app')

@section('title', 'Assuntos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Assuntos</h1>
        <a href="{{ route('assuntos.create') }}" class="btn btn-primary">Novo Assunto</a>
    </div>

    @if ($assuntos->isEmpty())
        <p class="text-muted">Nenhum assunto cadastrado ainda.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assuntos as $assunto)
                        <tr>
                            <td>{{ $assunto->descricao }}</td>
                            <td class="text-end">
                                <a href="{{ route('assuntos.edit', $assunto) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('assuntos.destroy', $assunto) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este assunto?');">
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
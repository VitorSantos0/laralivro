@extends('layouts.app')

@section('title', 'Editar Autor')

@section('content')
    <h1 class="h4 mb-3">Editar Autor</h1>

    <form action="{{ route('autores.update', $autor) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control @error('nome') is-invalid @enderror"
                   id="nome"
                   name="nome"
                   value="{{ old('nome', $autor->nome) }}"
                   maxlength="40">
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
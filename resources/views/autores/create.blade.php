@extends('layouts.app')

@section('title', 'Novo Autor')

@section('content')
    <h1 class="h4 mb-3">Novo Autor</h1>

    <form action="{{ route('autores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text"
                   class="form-control @error('nome') is-invalid @enderror"
                   id="nome"
                   name="nome"
                   value="{{ old('nome') }}"
                   maxlength="40"
                   autofocus>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
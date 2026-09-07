@extends('layouts.app')

@section('title', 'Editar Assunto')

@section('content')
    <h1 class="h4 mb-3">Editar Assunto</h1>

    <form action="{{ route('assuntos.update', $assunto) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control @error('descricao') is-invalid @enderror"
                   id="descricao"
                   name="descricao"
                   value="{{ old('descricao', $assunto->descricao) }}"
                   maxlength="20">
            @error('descricao')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('assuntos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection

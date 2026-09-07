@extends('layouts.app')

@section('title', 'Novo Livro')

@section('content')
    <h1 class="h4 mb-3">Novo Livro</h1>

    <form action="{{ route('livros.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control @error('titulo') is-invalid @enderror"
                   id="titulo"
                   name="titulo"
                   value="{{ old('titulo') }}"
                   maxlength="40"
                   autofocus>
            @error('titulo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="editora" class="form-label">Editora <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control @error('editora') is-invalid @enderror"
                   id="editora"
                   name="editora"
                   value="{{ old('editora') }}"
                   maxlength="40">
            @error('editora')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="edicao" class="form-label">Edição <span class="text-danger">*</span></label>
                <input type="number"
                       class="form-control @error('edicao') is-invalid @enderror"
                       id="edicao"
                       name="edicao"
                       value="{{ old('edicao') }}"
                       min="1">
                @error('edicao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="ano_publicacao" class="form-label">Ano de Publicação <span class="text-danger">*</span></label>
                <input type="number"
                       class="form-control @error('ano_publicacao') is-invalid @enderror"
                       id="ano_publicacao"
                       name="ano_publicacao"
                       value="{{ old('ano_publicacao') }}"
                       min="1000"
                       max="9999">
                @error('ano_publicacao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                @php
                    $valorAntigo = old('valor');
                    $valorExibicao = is_numeric($valorAntigo) ? number_format((float) $valorAntigo, 2, ',', '.') : $valorAntigo;
                @endphp
                <label for="valor_display" class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                <input type="text"
                       inputmode="decimal"
                       class="form-control @error('valor') is-invalid @enderror"
                       id="valor_display"
                       value="{{ $valorExibicao }}"
                       placeholder="0,00"
                       autocomplete="off">
                <input type="hidden" id="valor" name="valor" value="{{ $valorAntigo }}">
                @error('valor')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Autor(es) <span class="text-danger">*</span></label>
            <div class="border rounded p-2 @error('autores') border-danger @enderror" style="max-height: 200px; overflow-y: auto;">
                @foreach ($autores as $autor)
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="autor_{{ $autor->codau }}"
                               name="autores[]"
                               value="{{ $autor->codau }}"
                               @checked(in_array($autor->codau, old('autores', [])))>
                        <label class="form-check-label" for="autor_{{ $autor->codau }}">{{ $autor->nome }}</label>
                    </div>
                @endforeach
            </div>
            @error('autores')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Assunto(s) <span class="text-danger">*</span></label>
            <div class="border rounded p-2 @error('assuntos') border-danger @enderror" style="max-height: 200px; overflow-y: auto;">
                @foreach ($assuntos as $assunto)
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="assunto_{{ $assunto->codas }}"
                               name="assuntos[]"
                               value="{{ $assunto->codas }}"
                               @checked(in_array($assunto->codas, old('assuntos', [])))>
                        <label class="form-check-label" for="assunto_{{ $assunto->codas }}">{{ $assunto->descricao }}</label>
                    </div>
                @endforeach
            </div>
            @error('assuntos')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <script>
        (function () {
            const display = document.getElementById('valor_display');
            const hidden = document.getElementById('valor');

            display.addEventListener('input', function () {
                let digitos = display.value.replace(/\D/g, '') || '0';
                const numero = parseInt(digitos, 10) / 100;
                display.value = numero.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                hidden.value = numero.toFixed(2);
            });
        })();
    </script>
@endsection

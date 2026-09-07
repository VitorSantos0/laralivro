@extends('layouts.app')

@section('title', 'Início')

@section('content')
    <h1 class="h4 mb-4">Gerenciamento de Livros</h1>

    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h2 class="h5 card-title">Livros</h2>
                    <p class="card-text text-muted flex-grow-1">
                        Cadastre livros, vinculando autores, assuntos e valor.
                    </p>
                    <a href="{{ route('livros.index') }}" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h2 class="h5 card-title">Autores</h2>
                    <p class="card-text text-muted flex-grow-1">
                        Gerencie o cadastro de autores.
                    </p>
                    <a href="{{ route('autores.index') }}" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h2 class="h5 card-title">Assuntos</h2>
                    <p class="card-text text-muted flex-grow-1">
                        Gerencie o cadastro de assuntos.
                    </p>
                    <a href="{{ route('assuntos.index') }}" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
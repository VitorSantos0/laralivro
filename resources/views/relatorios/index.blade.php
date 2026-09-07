@extends('layouts.app')

@section('title', 'Relatórios')

@section('content')
    <h1 class="h4 mb-3">Relatórios</h1>

    <div class="list-group">
        <a href="{{ route('relatorios.livros-por-autor') }}" class="list-group-item list-group-item-action">
            <div class="fw-semibold">Livros por Autor</div>
            <small class="text-muted">Lista os livros cadastrados agrupados pelo autor.</small>
        </a>
    </div>
@endsection

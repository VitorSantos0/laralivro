<?php

namespace App\Repositories;

use App\Models\Livro;
use Illuminate\Database\Eloquent\Collection;

interface LivroRepositoryInterface
{
    public function all(): Collection;

    public function findOrFail(int $id): Livro;

    public function create(array $data): Livro;

    public function update(Livro $livro, array $data): Livro;

    public function delete(Livro $livro): void;

    public function syncAutores(Livro $livro, array $autorIds): void;

    public function syncAssuntos(Livro $livro, array $assuntoIds): void;

    public function detachAutores(Livro $livro): void;

    public function detachAssuntos(Livro $livro): void;
}

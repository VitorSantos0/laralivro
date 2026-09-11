<?php

namespace App\Repositories\Eloquent;

use App\Models\Livro;
use App\Repositories\LivroRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentLivroRepository implements LivroRepositoryInterface
{
    public function all(): Collection
    {
        return Livro::with(['autores', 'assuntos'])->get();
    }

    public function findOrFail(int $id): Livro
    {
        return Livro::with(['autores', 'assuntos'])->findOrFail($id);
    }

    public function create(array $data): Livro
    {
        return Livro::create($data);
    }

    public function update(Livro $livro, array $data): Livro
    {
        $livro->update($data);

        return $livro;
    }

    public function delete(Livro $livro): void
    {
        $livro->delete();
    }

    public function syncAutores(Livro $livro, array $autorIds): void
    {
        $livro->autores()->sync($autorIds);
    }

    public function syncAssuntos(Livro $livro, array $assuntoIds): void
    {
        $livro->assuntos()->sync($assuntoIds);
    }

    public function detachAutores(Livro $livro): void
    {
        $livro->autores()->detach();
    }

    public function detachAssuntos(Livro $livro): void
    {
        $livro->assuntos()->detach();
    }
}

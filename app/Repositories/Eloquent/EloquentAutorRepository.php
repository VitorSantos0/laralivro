<?php

namespace App\Repositories\Eloquent;

use App\Models\Autor;
use App\Repositories\AutorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentAutorRepository implements AutorRepositoryInterface
{
    public function all(): Collection
    {
        return Autor::all();
    }

    public function findOrFail(int $id): Autor
    {
        return Autor::findOrFail($id);
    }

    public function create(array $data): Autor
    {
        return Autor::create($data);
    }

    public function update(Autor $autor, array $data): Autor
    {
        $autor->update($data);

        return $autor;
    }

    public function delete(Autor $autor): void
    {
        $autor->delete();
    }
}

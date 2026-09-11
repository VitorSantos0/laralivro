<?php

namespace App\Repositories\Eloquent;

use App\Models\Assunto;
use App\Repositories\AssuntoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentAssuntoRepository implements AssuntoRepositoryInterface
{
    public function all(): Collection
    {
        return Assunto::all();
    }

    public function findOrFail(int $id): Assunto
    {
        return Assunto::findOrFail($id);
    }

    public function create(array $data): Assunto
    {
        return Assunto::create($data);
    }

    public function update(Assunto $assunto, array $data): Assunto
    {
        $assunto->update($data);

        return $assunto;
    }

    public function delete(Assunto $assunto): void
    {
        $assunto->delete();
    }
}

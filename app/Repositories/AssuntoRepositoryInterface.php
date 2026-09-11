<?php

namespace App\Repositories;

use App\Models\Assunto;
use Illuminate\Database\Eloquent\Collection;

interface AssuntoRepositoryInterface
{
    public function all(): Collection;

    public function findOrFail(int $id): Assunto;

    public function create(array $data): Assunto;

    public function update(Assunto $assunto, array $data): Assunto;

    public function delete(Assunto $assunto): void;
}

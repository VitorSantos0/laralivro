<?php

namespace App\Repositories;

use App\Models\Autor;

interface AutorRepositoryInterface extends AutorListInterface
{
    public function findOrFail(int $id): Autor;

    public function create(array $data): Autor;

    public function update(Autor $autor, array $data): Autor;

    public function delete(Autor $autor): void;
}

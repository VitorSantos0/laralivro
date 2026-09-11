<?php

namespace App\Services;

use App\Models\Livro;
use Illuminate\Database\Eloquent\Collection;

interface LivroServiceInterface
{
    public function all(): Collection;

    public function findOrFail(int $id): Livro;

    public function create(array $data): Livro;

    public function update(Livro $livro, array $data): Livro;

    public function delete(Livro $livro): void;

    /**
     * @return array{autores: Collection, assuntos: Collection}
     */
    public function formOptions(): array;
}

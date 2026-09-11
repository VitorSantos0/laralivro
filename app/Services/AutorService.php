<?php

namespace App\Services;

use App\Models\Autor;
use App\Repositories\AutorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AutorService extends Service implements AutorServiceInterface
{
    public function __construct(private readonly AutorRepositoryInterface $repository)
    {
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findOrFail(int $id): Autor
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Autor
    {
        return $this->repository->create($data);
    }

    public function update(Autor $autor, array $data): Autor
    {
        return $this->repository->update($autor, $data);
    }

    public function delete(Autor $autor): void
    {
        $this->transactional(
            fn () => $this->repository->delete($autor),
            'Este autor(a) está vinculado a um ou mais livros.',
        );
    }
}

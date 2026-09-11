<?php

namespace App\Services;

use App\Models\Assunto;
use App\Repositories\AssuntoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AssuntoService extends Service implements AssuntoServiceInterface
{
    public function __construct(private readonly AssuntoRepositoryInterface $repository)
    {
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findOrFail(int $id): Assunto
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Assunto
    {
        return $this->repository->create($data);
    }

    public function update(Assunto $assunto, array $data): Assunto
    {
        return $this->repository->update($assunto, $data);
    }

    public function delete(Assunto $assunto): void
    {
        $this->transactional(
            fn () => $this->repository->delete($assunto),
            'Este assunto está vinculado a um ou mais livros.',
        );
    }
}

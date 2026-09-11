<?php

namespace App\Services;

use App\Models\Livro;
use App\Repositories\AssuntoListInterface;
use App\Repositories\AutorListInterface;
use App\Repositories\LivroRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LivroService extends Service implements LivroServiceInterface
{
    public function __construct(
        private readonly LivroRepositoryInterface $repository,
        private readonly AutorListInterface $autorRepository,
        private readonly AssuntoListInterface $assuntoRepository,
    ) {
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findOrFail(int $id): Livro
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Livro
    {
        return $this->transactional(function () use ($data) {
            $livro = $this->repository->create($data);
            $this->repository->syncAutores($livro, $data['autores']);
            $this->repository->syncAssuntos($livro, $data['assuntos']);

            return $livro;
        });
    }

    public function update(Livro $livro, array $data): Livro
    {
        return $this->transactional(function () use ($livro, $data) {
            $livro = $this->repository->update($livro, $data);
            $this->repository->syncAutores($livro, $data['autores']);
            $this->repository->syncAssuntos($livro, $data['assuntos']);

            return $livro;
        });
    }

    public function delete(Livro $livro): void
    {
        $this->transactional(function () use ($livro) {
            $this->repository->detachAutores($livro);
            $this->repository->detachAssuntos($livro);
            $this->repository->delete($livro);
        });
    }

    public function formOptions(): array
    {
        return [
            'autores' => $this->autorRepository->all(),
            'assuntos' => $this->assuntoRepository->all(),
        ];
    }
}

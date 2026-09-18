<?php

namespace App\Services;

use App\Repositories\RelatorioRepositoryInterface;
use Illuminate\Support\Collection;

class RelatorioService implements RelatorioServiceInterface
{
    public function __construct(private readonly RelatorioRepositoryInterface $repository)
    {
    }

    public function livrosPorAutorAgrupados(): Collection
    {
        return $this->repository->livrosPorAutor()->groupBy('autor');
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface RelatorioRepositoryInterface
{
    public function livrosPorAutor(): Collection;
}

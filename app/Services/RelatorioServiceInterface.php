<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface RelatorioServiceInterface
{
    public function livrosPorAutorAgrupados(): Collection;
}

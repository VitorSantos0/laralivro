<?php

namespace App\Repositories\Eloquent;

use App\Repositories\RelatorioRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentRelatorioRepository implements RelatorioRepositoryInterface
{
    public function livrosPorAutor(): Collection
    {
        return DB::table('vw_relatorio_livros_por_autor')->get();
    }
}

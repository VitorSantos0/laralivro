<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RelatorioService
{
    public function getLivrosPorAutor()
    {
        return DB::table('vw_relatorio_livros_por_autor')
            ->get()
            ->groupBy('autor');
    }
}

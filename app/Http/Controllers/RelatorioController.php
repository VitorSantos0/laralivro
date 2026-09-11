<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index()
    {
        return view('relatorios.index');
    }

    public function livrosPorAutor()
    {
        $livrosPorAutor = $this->livrosPorAutorAgrupados();

        return view('relatorios.livros-por-autor', compact('livrosPorAutor'));
    }

    public function livrosPorAutorPdf()
    {
        $livrosPorAutor = $this->livrosPorAutorAgrupados();

        $pdf = Pdf::loadView('relatorios.livros-por-autor-pdf', compact('livrosPorAutor'));

        return $pdf->download('relatorio-livros-por-autor.pdf');
    }

    private function livrosPorAutorAgrupados()
    {
        return DB::table('vw_relatorio_livros_por_autor')
            ->get()
            ->groupBy('autor');
    }
}

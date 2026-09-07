<?php

namespace App\Http\Controllers;

use App\Services\RelatorioService;

use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function __construct(protected RelatorioService $relatorioService)
    {
    }

    public function index()
    {
        return view('relatorios.index');
    }

    public function livrosPorAutor()
    {
        $livrosPorAutor = $this->relatorioService->getLivrosPorAutor();
        return view('relatorios.livros-por-autor', compact('livrosPorAutor'));
    }

    public function livrosPorAutorPdf()
    {
        $livrosPorAutor = $this->relatorioService->getLivrosPorAutor();

        $pdf = Pdf::loadView('relatorios.livros-por-autor-pdf', compact('livrosPorAutor'));

        return $pdf->download('relatorio-livros-por-autor.pdf');
    }
}

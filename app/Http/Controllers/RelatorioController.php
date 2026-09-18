<?php

namespace App\Http\Controllers;

use App\Services\RelatorioServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function __construct(private readonly RelatorioServiceInterface $relatorioService)
    {
    }

    public function index()
    {
        return view('relatorios.index');
    }

    public function livrosPorAutor()
    {
        $livrosPorAutor = $this->relatorioService->livrosPorAutorAgrupados();

        return view('relatorios.livros-por-autor', compact('livrosPorAutor'));
    }

    public function livrosPorAutorPdf()
    {
        $livrosPorAutor = $this->relatorioService->livrosPorAutorAgrupados();

        $pdf = Pdf::loadView('relatorios.livros-por-autor-pdf', compact('livrosPorAutor'));

        return $pdf->download('relatorio-livros-por-autor.pdf');
    }
}

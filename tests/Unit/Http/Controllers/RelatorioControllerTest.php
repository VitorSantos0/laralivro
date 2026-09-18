<?php

namespace Tests\Unit\Http\Controllers;

use App\Services\RelatorioServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RelatorioControllerTest extends TestCase
{
    #[Test]
    public function index_retorna_a_view_de_relatorios_disponiveis(): void
    {
        $response = $this->get(route('relatorios.index'));

        $response->assertOk();
        $response->assertViewIs('relatorios.index');
    }

    #[Test]
    public function livros_por_autor_exibe_os_dados_agrupados_retornados_pelo_service(): void
    {
        $livrosPorAutor = new Collection(['Machado de Assis' => new Collection([(object) [
            'livro' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
            'assuntos' => 'Romance',
        ]])]);

        $this->mock(RelatorioServiceInterface::class, function ($mock) use ($livrosPorAutor) {
            $mock->shouldReceive('livrosPorAutorAgrupados')->once()->andReturn($livrosPorAutor);
        });

        $response = $this->get(route('relatorios.livros-por-autor'));

        $response->assertOk();
        $response->assertViewIs('relatorios.livros-por-autor');
        $response->assertViewHas('livrosPorAutor', $livrosPorAutor);
    }

    #[Test]
    public function pdf_delega_a_consulta_para_o_service_e_devolve_o_download_gerado_pelo_pdf(): void
    {
        $livrosPorAutor = new Collection(['Machado de Assis' => new Collection([(object) [
            'livro' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
            'assuntos' => 'Romance',
        ]])]);

        $this->mock(RelatorioServiceInterface::class, function ($mock) use ($livrosPorAutor) {
            $mock->shouldReceive('livrosPorAutorAgrupados')->once()->andReturn($livrosPorAutor);
        });

        $pdfMock = Mockery::mock(DomPdf::class);
        $pdfMock->shouldReceive('download')
            ->once()
            ->with('relatorio-livros-por-autor.pdf')
            ->andReturn(response('conteudo-pdf', 200, ['Content-Type' => 'application/pdf']));

        Pdf::shouldReceive('loadView')
            ->once()
            ->with('relatorios.livros-por-autor-pdf', ['livrosPorAutor' => $livrosPorAutor])
            ->andReturn($pdfMock);

        $response = $this->get(route('relatorios.livros-por-autor.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}

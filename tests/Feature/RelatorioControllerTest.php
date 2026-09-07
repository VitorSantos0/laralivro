<?php

namespace Tests\Feature\Relatorio;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Autor;
use App\Models\Assunto;
use App\Models\Livro;

use PHPUnit\Framework\Attributes\Test;

class RelatorioControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function rota_index_retorna_a_lista_de_relatorios_disponiveis(): void
    {
        $response = $this->get(route('relatorios.index'));

        $response->assertOk();
        $response->assertViewIs('relatorios.index');
        $response->assertSee(route('relatorios.livros-por-autor'), false);
    }

    #[Test]
    public function rota_livros_por_autor_retorna_a_view_correta(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $assunto = Assunto::create(['descricao' => 'Romance']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro->autores()->attach($autor->codau);
        $livro->assuntos()->attach($assunto->codas);

        $response = $this->get(route('relatorios.livros-por-autor'));

        $response->assertOk();
        $response->assertViewIs('relatorios.livros-por-autor');
    }

    #[Test]
    public function dados_chegam_agrupados_por_autor_na_view(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $assunto = Assunto::create(['descricao' => 'Romance']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro->autores()->attach($autor->codau);
        $livro->assuntos()->attach($assunto->codas);

        $response = $this->get(route('relatorios.livros-por-autor'));

        $response->assertViewHas('livrosPorAutor', function ($livrosPorAutor) use ($autor) {
            return $livrosPorAutor->has($autor->nome)
                && $livrosPorAutor->get($autor->nome)->count() === 1
                && $livrosPorAutor->get($autor->nome)->first()->livro === 'Dom Casmurro';
        });
    }

    #[Test]
    public function livro_com_multiplos_autores_gera_uma_entrada_para_cada_autor_no_agrupamento(): void
    {
        $ilana = Autor::create(['nome' => 'Ilana Casoy']);
        $raphael = Autor::create(['nome' => 'Raphael Montes']);
        $assunto = Assunto::create(['descricao' => 'Suspense']);
        $livro = Livro::create([
            'titulo' => 'Bom Dia, Verônica',
            'editora' => 'Companhia das Letras',
            'edicao' => 2,
            'ano_publicacao' => 2022,
            'valor' => 52.40,
        ]);
        $livro->autores()->attach([$ilana->codau, $raphael->codau]);
        $livro->assuntos()->attach($assunto->codas);

        $response = $this->get(route('relatorios.livros-por-autor'));

        $response->assertViewHas('livrosPorAutor', function ($livrosPorAutor) use ($ilana, $raphael) {
            return $livrosPorAutor->has($ilana->nome)
                && $livrosPorAutor->has($raphael->nome)
                && $livrosPorAutor->get($ilana->nome)->count() === 1
                && $livrosPorAutor->get($raphael->nome)->count() === 1;
        });
    }

    #[Test]
    public function rota_pdf_retorna_arquivo_pdf_para_download(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $assunto = Assunto::create(['descricao' => 'Romance']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro->autores()->attach($autor->codau);
        $livro->assuntos()->attach($assunto->codas);

        $response = $this->get(route('relatorios.livros-por-autor.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}

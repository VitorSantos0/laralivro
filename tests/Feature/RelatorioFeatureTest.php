<?php

namespace Tests\Feature\Relatorio;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\Autor;
use App\Models\Assunto;
use App\Models\Livro;

use PHPUnit\Framework\Attributes\Test;


class RelatorioFeatureTest extends TestCase
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

    #[Test]
    public function view_existe_no_banco(): void
    {
        $viewExiste = DB::selectOne("
            SELECT EXISTS (
                SELECT FROM pg_views WHERE viewname = 'vw_relatorio_livros_por_autor'
            ) AS existe
        ");

        $this->assertTrue($viewExiste->existe);
    }

    #[Test]
    public function livro_com_multiplos_autores_aparece_uma_vez_para_cada_autor(): void
    {
        $ilana = Autor::create(['nome' => 'Ilana Casoy']);
        $raphael = Autor::create(['nome' => 'Raphael Montes']);
        $suspense = Assunto::create(['descricao' => 'Suspense']);

        $livro = Livro::create([
            'titulo' => 'Bom Dia, Verônica',
            'editora' => 'Companhia das Letras',
            'edicao' => 2,
            'ano_publicacao' => 2022,
            'valor' => 52.40,
        ]);
        $livro->autores()->attach([$ilana->codau, $raphael->codau]);
        $livro->assuntos()->attach($suspense->codas);

        $resultado = DB::table('vw_relatorio_livros_por_autor')
            ->where('cod_livro', $livro->codl)
            ->get();

        $this->assertCount(2, $resultado);
        $this->assertEqualsCanonicalizing(
            ['Ilana Casoy', 'Raphael Montes'],
            $resultado->pluck('autor')->all()
        );
    }

    #[Test]
    public function view_traz_assuntos_concatenados_do_livro(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $romance = Assunto::create(['descricao' => 'Romance']);
        $ficcao = Assunto::create(['descricao' => 'Ficção']);

        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro->autores()->attach($autor->codau);
        $livro->assuntos()->attach([$romance->codas, $ficcao->codas]);

        $resultado = DB::table('vw_relatorio_livros_por_autor')
            ->where('cod_livro', $livro->codl)
            ->first();

        $this->assertEquals('Ficção, Romance', $resultado->assuntos);
    }
}

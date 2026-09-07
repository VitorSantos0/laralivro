<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

use App\Models\Autor;
use App\Models\Assunto;
use App\Models\Livro;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class RelatorioLivrosPorAutorTest extends TestCase
{
    use RefreshDatabase;

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

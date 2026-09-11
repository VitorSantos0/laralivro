<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Autor;
use App\Models\Livro;
use App\Models\LivroAutor;

use PHPUnit\Framework\Attributes\Test;

class LivroAutorRelationshipTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_vincular_um_autor_a_um_livro(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);

        LivroAutor::create([
            'livro_codl' => $livro->codl,
            'autor_codau' => $autor->codau,
        ]);

        $this->assertDatabaseHas('livro_autor', [
            'livro_codl' => $livro->codl,
            'autor_codau' => $autor->codau,
        ]);
    }

    #[Test]
    public function getKeyName_do_pivot_respeita_o_contrato_do_eloquent(): void
    {
        $vinculo = LivroAutor::create([
            'livro_codl' => Livro::create([
                'titulo' => 'Dom Casmurro',
                'editora' => 'Editora X',
                'edicao' => 1,
                'ano_publicacao' => 1899,
                'valor' => 39.90,
            ])->codl,
            'autor_codau' => Autor::create(['nome' => 'Machado de Assis'])->codau,
        ]);

        $this->assertIsString($vinculo->getKeyName());
        $this->assertNull($vinculo->getKey());
    }

    #[Test]
    public function consegue_remover_um_vinculo_entre_autor_e_livro(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);

        $vinculo = LivroAutor::create([
            'livro_codl' => $livro->codl,
            'autor_codau' => $autor->codau,
        ]);

        $vinculo->delete();

        $this->assertDatabaseMissing('livro_autor', [
            'livro_codl' => $livro->codl,
            'autor_codau' => $autor->codau,
        ]);
    }

    #[Test]
    public function consegue_listar_autores_de_um_livro(): void
    {
        $autor1 = Autor::create(['nome' => 'Ilana Casoy']);
        $autor2 = Autor::create(['nome' => 'Raphael Montes']);

        $livro = Livro::create([
            'titulo' => 'Bom Dia, Verônica',
            'editora' => 'Companhia das Letras',
            'edicao' => 2,
            'ano_publicacao' => 2022,
            'valor' => 52.40,
        ]);

        LivroAutor::create([
            'livro_codl' => $livro->codl,
            'autor_codau' => $autor1->codau,
        ]);

        LivroAutor::create([
            'livro_codl' => $livro->codl,
            'autor_codau' => $autor2->codau,
        ]); 

        $this->assertCount(2, $livro->autores);
        $this->assertEqualsCanonicalizing(
            ['Ilana Casoy', 'Raphael Montes'],
            $livro->autores->pluck('nome')->all()
        );
    }
}

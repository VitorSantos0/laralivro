<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Autor;
use App\Models\Livro;

use PHPUnit\Framework\Attributes\Test;

class AutorControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function nome_do_autor_deve_ser_obrigatorio(): void
    {
        $response = $this->post(route('autores.store'), [
            'nome' => '',
        ]);

        $response->assertSessionHasErrors('nome');
    }

    #[Test]
    public function nome_do_autor_deve_ter_no_minimo_3_caracteres(): void
    {
        $response = $this->post(route('autores.store'), [
            'nome' => 'Jo',
        ]);

        $response->assertSessionHasErrors('nome');
    }

    #[Test]
    public function nome_do_autor_nao_deve_ultrapassar_40_caracteres(): void
    {
        $response = $this->post(route('autores.store'), [
            'nome' => str_repeat('a', 41),
        ]);

        $response->assertSessionHasErrors('nome');
    }

    #[Test]
    public function pode_criar_um_autor(): void
    {
        $response = $this->post(route('autores.store'), [
            'nome' => 'João da Silva',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('autor', [
            'nome' => 'João da Silva',
        ]);
    }

    #[Test]
    public function pode_atualizar_um_autor(): void
    {
        $autor = Autor::create(['nome' => 'Nome Antigo']);

        $response = $this->put(route('autores.update', $autor), [
            'nome' => 'Nome Atualizado',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('autor', [
            'codau' => $autor->codau,
            'nome' => 'Nome Atualizado',
        ]);
    }

    #[Test]
    public function pode_excluir_um_autor(): void
    {
        $autor = Autor::create(['nome' => 'Autor Para Excluir']);

        $response = $this->delete(route('autores.destroy', $autor));

        $response->assertRedirect(route('autores.index'));
        $this->assertDatabaseMissing('autor', [
            'codau' => $autor->codau,
        ]);
    }

    #[Test]
    public function listar_autores_cadastrados(): void
    {
        Autor::create(['nome' => 'Clarice Lispector']);
        Autor::create(['nome' => 'Machado de Assis']);

        $response = $this->get(route('autores.index'));

        $response->assertOk();
        $response->assertViewIs('autores.index');
        $response->assertViewHas('autores', function ($autores) {
            return $autores->count() === 2;
        });
    }

    #[Test]
    public function nao_deve_excluir_autor_vinculado_a_um_livro(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro->autores()->attach($autor->codau);

        $response = $this->delete(route('autores.destroy', $autor));

        $this->assertDatabaseHas('autor', [
            'codau' => $autor->codau,
        ]);
    }
}

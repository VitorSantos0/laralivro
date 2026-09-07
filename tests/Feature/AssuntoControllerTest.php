<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Assunto;
use App\Models\Livro;

use PHPUnit\Framework\Attributes\Test;

class AssuntoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function descricao_do_assunto_deve_ser_obrigatoria(): void
    {
        $response = $this->post(route('assuntos.store'), [
            'descricao' => '',
        ]);

        $response->assertSessionHasErrors('descricao');
    }

    #[Test]
    public function descricao_do_assunto_deve_ter_no_minimo_3_caracteres(): void
    {
        $response = $this->post(route('assuntos.store'), [
            'descricao' => 'Ro',
        ]);

        $response->assertSessionHasErrors('descricao');
    }

    #[Test]
    public function descricao_do_assunto_nao_deve_ultrapassar_20_caracteres(): void
    {
        $response = $this->post(route('assuntos.store'), [
            'descricao' => str_repeat('a', 21),
        ]);

        $response->assertSessionHasErrors('descricao');
    }

    #[Test]
    public function pode_criar_um_assunto(): void
    {
        $response = $this->post(route('assuntos.store'), [
            'descricao' => 'Romance',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('assunto', [
            'descricao' => 'Romance',
        ]);
    }

    #[Test]
    public function pode_atualizar_um_assunto(): void
    {
        $assunto = Assunto::create(['descricao' => 'Descricao Antiga']);

        $response = $this->put(route('assuntos.update', $assunto), [
            'descricao' => 'Descricao Nova',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('assunto', [
            'codas' => $assunto->codas,
            'descricao' => 'Descricao Nova',
        ]);
    }

    #[Test]
    public function pode_excluir_um_assunto(): void
    {
        $assunto = Assunto::create(['descricao' => 'Assunto Para Excluir']);

        $response = $this->delete(route('assuntos.destroy', $assunto));

        $response->assertRedirect(route('assuntos.index'));
        $this->assertDatabaseMissing('assunto', [
            'codas' => $assunto->codas,
        ]);
    }

    #[Test]
    public function nao_deve_excluir_assunto_vinculado_a_um_livro(): void
    {
        $assunto = Assunto::create(['descricao' => 'Romance']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro->assuntos()->attach($assunto->codas);

        $response = $this->delete(route('assuntos.destroy', $assunto));

        $this->assertDatabaseHas('assunto', [
            'codas' => $assunto->codas,
        ]);
    }

    #[Test]
    public function listar_assuntos_cadastrados(): void
    {
        Assunto::create(['descricao' => 'Romance']);
        Assunto::create(['descricao' => 'Ficção']);

        $response = $this->get(route('assuntos.index'));

        $response->assertOk();
        $response->assertViewIs('assuntos.index');
        $response->assertViewHas('assuntos', function ($assuntos) {
            return $assuntos->count() === 2;
        });
    }
}

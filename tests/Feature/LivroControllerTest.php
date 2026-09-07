<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Autor;
use App\Models\Assunto;
use App\Models\Livro;

use PHPUnit\Framework\Attributes\Test;

class LivroControllerTest extends TestCase
{
    use RefreshDatabase;

    private function dadosValidos(array $overrides = []): array
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $assunto = Assunto::create(['descricao' => 'Romance']);

        return array_merge([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora Ática',
            'edicao' => 3,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
            'autores' => [$autor->codau],
            'assuntos' => [$assunto->codas],
        ], $overrides);
    }

    #[Test]
    public function titulo_do_livro_deve_ser_obrigatorio(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['titulo' => '']));

        $response->assertSessionHasErrors('titulo');
    }

    #[Test]
    public function titulo_do_livro_deve_ter_no_minimo_3_caracteres(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['titulo' => 'Ab']));

        $response->assertSessionHasErrors('titulo');
    }

    #[Test]
    public function titulo_do_livro_nao_deve_ultrapassar_40_caracteres(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['titulo' => str_repeat('a', 41)]));

        $response->assertSessionHasErrors('titulo');
    }

    #[Test]
    public function editora_do_livro_deve_ser_obrigatoria(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['editora' => '']));

        $response->assertSessionHasErrors('editora');
    }

    #[Test]
    public function edicao_do_livro_deve_ser_obrigatoria(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['edicao' => '']));

        $response->assertSessionHasErrors('edicao');
    }

    #[Test]
    public function edicao_do_livro_deve_ser_um_numero_inteiro(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['edicao' => 'abc']));

        $response->assertSessionHasErrors('edicao');
    }

    #[Test]
    public function ano_publicacao_do_livro_deve_ser_obrigatorio(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['ano_publicacao' => '']));

        $response->assertSessionHasErrors('ano_publicacao');
    }

    #[Test]
    public function valor_do_livro_deve_ser_obrigatorio(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['valor' => '']));

        $response->assertSessionHasErrors('valor');
    }

    #[Test]
    public function valor_do_livro_deve_ser_numerico(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['valor' => 'abc']));

        $response->assertSessionHasErrors('valor');
    }

    #[Test]
    public function livro_deve_ter_ao_menos_um_autor(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['autores' => []]));

        $response->assertSessionHasErrors('autores');
    }

    #[Test]
    public function livro_deve_ter_ao_menos_um_assunto(): void
    {
        $response = $this->post(route('livros.store'), $this->dadosValidos(['assuntos' => []]));

        $response->assertSessionHasErrors('assuntos');
    }

    #[Test]
    public function pode_criar_um_livro(): void
    {
        $dados = $this->dadosValidos();

        $response = $this->post(route('livros.store'), $dados);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('livro', [
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora Ática',
        ]);

        $livro = Livro::where('titulo', 'Dom Casmurro')->firstOrFail();
        $this->assertDatabaseHas('livro_autor', [
            'livro_codl' => $livro->codl,
            'autor_codau' => $dados['autores'][0],
        ]);
        $this->assertDatabaseHas('livro_assunto', [
            'livro_codl' => $livro->codl,
            'assunto_codas' => $dados['assuntos'][0],
        ]);
    }

    #[Test]
    public function pode_atualizar_um_livro(): void
    {
        $autorAntigo = Autor::create(['nome' => 'Autor Antigo']);
        $assuntoAntigo = Assunto::create(['descricao' => 'Antigo']);
        $livro = Livro::create([
            'titulo' => 'Titulo Antigo',
            'editora' => 'Editora Antiga',
            'edicao' => 1,
            'ano_publicacao' => 2000,
            'valor' => 10.00,
        ]);
        $livro->autores()->attach($autorAntigo->codau);
        $livro->assuntos()->attach($assuntoAntigo->codas);

        $autorNovo = Autor::create(['nome' => 'Autor Novo']);
        $assuntoNovo = Assunto::create(['descricao' => 'Novo']);

        $response = $this->put(route('livros.update', $livro), [
            'titulo' => 'Titulo Novo',
            'editora' => 'Editora Nova',
            'edicao' => 2,
            'ano_publicacao' => 2020,
            'valor' => 59.90,
            'autores' => [$autorNovo->codau],
            'assuntos' => [$assuntoNovo->codas],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('livro', [
            'codl' => $livro->codl,
            'titulo' => 'Titulo Novo',
        ]);
        $this->assertDatabaseHas('livro_autor', [
            'livro_codl' => $livro->codl,
            'autor_codau' => $autorNovo->codau,
        ]);
        $this->assertDatabaseMissing('livro_autor', [
            'livro_codl' => $livro->codl,
            'autor_codau' => $autorAntigo->codau,
        ]);
        $this->assertDatabaseHas('livro_assunto', [
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assuntoNovo->codas,
        ]);
        $this->assertDatabaseMissing('livro_assunto', [
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assuntoAntigo->codas,
        ]);
    }

    #[Test]
    public function pode_excluir_um_livro(): void
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

        $response = $this->delete(route('livros.destroy', $livro));

        $response->assertRedirect(route('livros.index'));
        $this->assertDatabaseMissing('livro', ['codl' => $livro->codl]);
        $this->assertDatabaseMissing('livro_autor', ['livro_codl' => $livro->codl]);
        $this->assertDatabaseMissing('livro_assunto', ['livro_codl' => $livro->codl]);
    }

    #[Test]
    public function listar_livros_cadastrados(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);
        $assunto = Assunto::create(['descricao' => 'Romance']);

        $livro1 = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $livro1->autores()->attach($autor->codau);
        $livro1->assuntos()->attach($assunto->codas);

        $livro2 = Livro::create([
            'titulo' => 'Memórias Póstumas',
            'editora' => 'Editora Y',
            'edicao' => 1,
            'ano_publicacao' => 1881,
            'valor' => 29.90,
        ]);
        $livro2->autores()->attach($autor->codau);
        $livro2->assuntos()->attach($assunto->codas);

        $response = $this->get(route('livros.index'));

        $response->assertOk();
        $response->assertViewIs('livros.index');
        $response->assertViewHas('livros', function ($livros) {
            return $livros->count() === 2;
        });
    }
}

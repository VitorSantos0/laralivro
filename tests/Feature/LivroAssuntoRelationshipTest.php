<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Assunto;
use App\Models\Livro;
use App\Models\LivroAssunto;

use PHPUnit\Framework\Attributes\Test;

class LivroAssuntoRelationshipTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_vincular_um_assunto_a_um_livro(): void
    {
        $assunto = Assunto::create(['descricao' => 'Romance']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);

        LivroAssunto::create([
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assunto->codas,
        ]);

        $this->assertDatabaseHas('livro_assunto', [
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assunto->codas,
        ]);
    }

    #[Test]
    public function getKeyName_do_pivot_respeita_o_contrato_do_eloquent(): void
    {
        $vinculo = LivroAssunto::create([
            'livro_codl' => Livro::create([
                'titulo' => 'Dom Casmurro',
                'editora' => 'Editora X',
                'edicao' => 1,
                'ano_publicacao' => 1899,
                'valor' => 39.90,
            ])->codl,
            'assunto_codas' => Assunto::create(['descricao' => 'Romance'])->codas,
        ]);

        $this->assertIsString($vinculo->getKeyName());
        $this->assertNull($vinculo->getKey());
    }

    #[Test]
    public function consegue_remover_um_vinculo_entre_assunto_e_livro(): void
    {
        $assunto = Assunto::create(['descricao' => 'Romance']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);

        $vinculo = LivroAssunto::create([
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assunto->codas,
        ]);

        $vinculo->delete();
        $this->assertDatabaseMissing('livro_assunto', [
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assunto->codas,
        ]);
    }

    #[Test]
    public function consegue_listar_assuntos_de_um_livro(): void
    {
        $assunto1 = Assunto::create(['descricao' => 'Romance']);
        $assunto2 = Assunto::create(['descricao' => 'Ficção']);
        $livro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);

        LivroAssunto::create([
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assunto1->codas,
        ]);

        LivroAssunto::create([
            'livro_codl' => $livro->codl,
            'assunto_codas' => $assunto2->codas,
        ]);

        $this->assertCount(2, $livro->assuntos);
        $this->assertEqualsCanonicalizing(
            [$assunto1->codas, $assunto2->codas],
            $livro->assuntos->pluck('codas')->toArray()
        );
    }
}

<?php

namespace Tests\Unit\Http\Controllers;

use App\Exceptions\RegistroVinculadoException;
use App\Models\Autor;
use App\Services\AutorServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AutorControllerTest extends TestCase
{
    #[Test]
    public function index_exibe_os_autores_retornados_pelo_service(): void
    {
        $autor = (new Autor(['nome' => 'Machado de Assis']))->forceFill(['codau' => 1]);
        $autores = new Collection([$autor]);

        $this->mock(AutorServiceInterface::class, function ($mock) use ($autores) {
            $mock->shouldReceive('all')->once()->andReturn($autores);
        });

        $response = $this->get(route('autores.index'));

        $response->assertOk();
        $response->assertViewHas('autores', $autores);
    }

    #[Test]
    public function store_envia_os_dados_validados_para_o_service_e_redireciona(): void
    {
        $this->mock(AutorServiceInterface::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->with(['nome' => 'Clarice Lispector'])
                ->andReturn(new Autor(['codau' => 1, 'nome' => 'Clarice Lispector']));
        });

        $response = $this->post(route('autores.store'), ['nome' => 'Clarice Lispector']);

        $response->assertRedirect(route('autores.index'));
        $response->assertSessionHas('success');
    }

    #[Test]
    public function update_busca_o_autor_e_delega_a_atualizacao_para_o_service(): void
    {
        $autor = new Autor(['codau' => 1, 'nome' => 'Nome Antigo']);

        $this->mock(AutorServiceInterface::class, function ($mock) use ($autor) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($autor);
            $mock->shouldReceive('update')->once()->with($autor, ['nome' => 'Nome Novo'])->andReturn($autor);
        });

        $response = $this->put(route('autores.update', 1), ['nome' => 'Nome Novo']);

        $response->assertRedirect(route('autores.index'));
    }

    #[Test]
    public function destroy_busca_o_autor_e_delega_a_exclusao_para_o_service(): void
    {
        $autor = new Autor(['codau' => 1, 'nome' => 'Machado de Assis']);

        $this->mock(AutorServiceInterface::class, function ($mock) use ($autor) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($autor);
            $mock->shouldReceive('delete')->once()->with($autor);
        });

        $response = $this->delete(route('autores.destroy', 1));

        $response->assertRedirect(route('autores.index'));
    }

    #[Test]
    public function destroy_converte_registro_vinculado_exception_em_mensagem_de_erro_na_sessao(): void
    {
        $autor = new Autor(['codau' => 1, 'nome' => 'Machado de Assis']);

        $this->mock(AutorServiceInterface::class, function ($mock) use ($autor) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($autor);
            $mock->shouldReceive('delete')
                ->once()
                ->with($autor)
                ->andThrow(new RegistroVinculadoException('Este autor(a) está vinculado a um ou mais livros.'));
        });

        $response = $this->delete(route('autores.destroy', 1));

        $response->assertSessionHas('error', 'Este autor(a) está vinculado a um ou mais livros.');
    }
}

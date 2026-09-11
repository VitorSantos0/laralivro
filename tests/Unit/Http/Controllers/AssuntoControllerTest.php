<?php

namespace Tests\Unit\Http\Controllers;

use App\Exceptions\RegistroVinculadoException;
use App\Models\Assunto;
use App\Services\AssuntoServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssuntoControllerTest extends TestCase
{
    #[Test]
    public function index_exibe_os_assuntos_retornados_pelo_service(): void
    {
        $assunto = (new Assunto(['descricao' => 'Romance']))->forceFill(['codas' => 1]);
        $assuntos = new Collection([$assunto]);

        $this->mock(AssuntoServiceInterface::class, function ($mock) use ($assuntos) {
            $mock->shouldReceive('all')->once()->andReturn($assuntos);
        });

        $response = $this->get(route('assuntos.index'));

        $response->assertOk();
        $response->assertViewHas('assuntos', $assuntos);
    }

    #[Test]
    public function store_envia_os_dados_validados_para_o_service_e_redireciona(): void
    {
        $this->mock(AssuntoServiceInterface::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->with(['descricao' => 'Ficção'])
                ->andReturn(new Assunto(['codas' => 1, 'descricao' => 'Ficção']));
        });

        $response = $this->post(route('assuntos.store'), ['descricao' => 'Ficção']);

        $response->assertRedirect(route('assuntos.index'));
        $response->assertSessionHas('success');
    }

    #[Test]
    public function update_busca_o_assunto_e_delega_a_atualizacao_para_o_service(): void
    {
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Antigo']);

        $this->mock(AssuntoServiceInterface::class, function ($mock) use ($assunto) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($assunto);
            $mock->shouldReceive('update')->once()->with($assunto, ['descricao' => 'Novo'])->andReturn($assunto);
        });

        $response = $this->put(route('assuntos.update', 1), ['descricao' => 'Novo']);

        $response->assertRedirect(route('assuntos.index'));
    }

    #[Test]
    public function destroy_busca_o_assunto_e_delega_a_exclusao_para_o_service(): void
    {
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Romance']);

        $this->mock(AssuntoServiceInterface::class, function ($mock) use ($assunto) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($assunto);
            $mock->shouldReceive('delete')->once()->with($assunto);
        });

        $response = $this->delete(route('assuntos.destroy', 1));

        $response->assertRedirect(route('assuntos.index'));
    }

    #[Test]
    public function destroy_converte_registro_vinculado_exception_em_mensagem_de_erro_na_sessao(): void
    {
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Romance']);

        $this->mock(AssuntoServiceInterface::class, function ($mock) use ($assunto) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($assunto);
            $mock->shouldReceive('delete')
                ->once()
                ->with($assunto)
                ->andThrow(new RegistroVinculadoException('Este assunto está vinculado a um ou mais livros.'));
        });

        $response = $this->delete(route('assuntos.destroy', 1));

        $response->assertSessionHas('error', 'Este assunto está vinculado a um ou mais livros.');
    }
}

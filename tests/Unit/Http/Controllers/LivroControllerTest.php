<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\LivroController;
use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use App\Services\LivroServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LivroControllerTest extends TestCase
{
    #[Test]
    public function index_exibe_os_livros_retornados_pelo_service(): void
    {
        $livro = (new Livro(['titulo' => 'Dom Casmurro', 'valor' => 39.90]))->forceFill(['codl' => 1]);
        $livro->setRelation('autores', new Collection([
            (new Autor(['nome' => 'Machado de Assis']))->forceFill(['codau' => 1]),
        ]));
        $livros = new Collection([$livro]);

        $this->mock(LivroServiceInterface::class, function ($mock) use ($livros) {
            $mock->shouldReceive('all')->once()->andReturn($livros);
        });

        $response = $this->get(route('livros.index'));

        $response->assertOk();
        $response->assertViewHas('livros', $livros);
    }

    #[Test]
    public function create_exibe_as_opcoes_de_autores_e_assuntos_do_service(): void
    {
        $opcoes = [
            'autores' => new Collection([new Autor(['codau' => 1, 'nome' => 'Machado de Assis'])]),
            'assuntos' => new Collection([new Assunto(['codas' => 1, 'descricao' => 'Romance'])]),
        ];

        $this->mock(LivroServiceInterface::class, function ($mock) use ($opcoes) {
            $mock->shouldReceive('formOptions')->once()->andReturn($opcoes);
        });

        $response = $this->get(route('livros.create'));

        $response->assertOk();
        $response->assertViewHas('autores', $opcoes['autores']);
        $response->assertViewHas('assuntos', $opcoes['assuntos']);
    }

    #[Test]
    public function destroy_busca_e_exclui_via_service(): void
    {
        $livro = new Livro(['codl' => 1, 'titulo' => 'Dom Casmurro']);

        $this->mock(LivroServiceInterface::class, function ($mock) use ($livro) {
            $mock->shouldReceive('findOrFail')->once()->with('1')->andReturn($livro);
            $mock->shouldReceive('delete')->once()->with($livro);
        });

        $response = $this->delete(route('livros.destroy', 1));

        $response->assertRedirect(route('livros.index'));
    }

    /**
     * store/update do Livro não dá para testar via rota real sem banco:
     * LivroRequest valida "autores"/"assuntos" com exists:autor,codau /
     * exists:assunto,codas, que são regras de validação acopladas ao banco
     * e ficam fora das camadas Service/Repository que este teste cobre.
     * Por isso o controller é instanciado direto e o FormRequest é mockado,
     * simulando a validação já resolvida.
     */
    #[Test]
    public function store_envia_os_dados_validados_para_o_service_e_redireciona(): void
    {
        $dados = [
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
            'autores' => [1],
            'assuntos' => [2],
        ];

        $service = Mockery::mock(LivroServiceInterface::class);
        $service->shouldReceive('create')->once()->with($dados)->andReturn(new Livro($dados));

        $request = Mockery::mock(LivroRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($dados);

        $response = (new LivroController($service))->store($request);

        $this->assertSame(route('livros.index'), $response->getTargetUrl());
        $this->assertSame('Livro cadastrado com sucesso.', $response->getSession()->get('success'));
    }

    #[Test]
    public function update_busca_o_livro_e_envia_os_dados_validados_para_o_service(): void
    {
        $livro = new Livro(['codl' => 1, 'titulo' => 'Titulo Antigo']);
        $dados = [
            'titulo' => 'Titulo Novo',
            'editora' => 'Editora X',
            'edicao' => 2,
            'ano_publicacao' => 2020,
            'valor' => 59.90,
            'autores' => [1],
            'assuntos' => [2],
        ];

        $service = Mockery::mock(LivroServiceInterface::class);
        $service->shouldReceive('findOrFail')->once()->with('1')->andReturn($livro);
        $service->shouldReceive('update')->once()->with($livro, $dados)->andReturn($livro);

        $request = Mockery::mock(LivroRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($dados);

        $response = (new LivroController($service))->update($request, '1');

        $this->assertSame(route('livros.index'), $response->getTargetUrl());
    }
}

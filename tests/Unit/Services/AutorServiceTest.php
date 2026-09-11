<?php

namespace Tests\Unit\Services;

use App\Exceptions\RegistroVinculadoException;
use App\Models\Autor;
use App\Repositories\AutorRepositoryInterface;
use App\Services\AutorService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Unit\Concerns\MakesQueryExceptions;
use Tests\Unit\Concerns\UsesInMemoryDatabase;

class AutorServiceTest extends TestCase
{
    use MakesQueryExceptions;
    use UsesInMemoryDatabase;

    #[Test]
    public function all_delega_para_o_repositorio(): void
    {
        $repository = Mockery::mock(AutorRepositoryInterface::class);
        $autores = new Collection([new Autor(['codau' => 1, 'nome' => 'Machado de Assis'])]);

        $repository->shouldReceive('all')->once()->andReturn($autores);

        $service = new AutorService($repository);

        $this->assertSame($autores, $service->all());
    }

    #[Test]
    public function create_delega_para_o_repositorio_com_os_dados_recebidos(): void
    {
        $repository = Mockery::mock(AutorRepositoryInterface::class);
        $autor = new Autor(['codau' => 1, 'nome' => 'Clarice Lispector']);

        $repository->shouldReceive('create')
            ->once()
            ->with(['nome' => 'Clarice Lispector'])
            ->andReturn($autor);

        $service = new AutorService($repository);

        $this->assertSame($autor, $service->create(['nome' => 'Clarice Lispector']));
    }

    #[Test]
    public function update_delega_para_o_repositorio(): void
    {
        $repository = Mockery::mock(AutorRepositoryInterface::class);
        $autor = new Autor(['codau' => 1, 'nome' => 'Nome Antigo']);

        $repository->shouldReceive('update')
            ->once()
            ->with($autor, ['nome' => 'Nome Novo'])
            ->andReturn($autor);

        $service = new AutorService($repository);

        $this->assertSame($autor, $service->update($autor, ['nome' => 'Nome Novo']));
    }

    #[Test]
    public function delete_delega_a_exclusao_para_o_repositorio(): void
    {
        $repository = Mockery::mock(AutorRepositoryInterface::class);
        $autor = new Autor(['codau' => 1, 'nome' => 'Machado de Assis']);

        $repository->shouldReceive('delete')->once()->with($autor);

        $service = new AutorService($repository);

        $service->delete($autor);

        $repository->shouldHaveReceived('delete')->once();
    }

    #[Test]
    public function delete_traduz_violacao_de_fk_em_registro_vinculado_exception(): void
    {
        $repository = Mockery::mock(AutorRepositoryInterface::class);
        $autor = new Autor(['codau' => 1, 'nome' => 'Machado de Assis']);

        $repository->shouldReceive('delete')
            ->once()
            ->with($autor)
            ->andThrow($this->foreignKeyViolation());

        $service = new AutorService($repository);

        $this->expectException(RegistroVinculadoException::class);
        $this->expectExceptionMessage('Este autor(a) está vinculado a um ou mais livros.');

        $service->delete($autor);
    }
}

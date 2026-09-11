<?php

namespace Tests\Unit\Services;

use App\Exceptions\RegistroVinculadoException;
use App\Models\Assunto;
use App\Repositories\AssuntoRepositoryInterface;
use App\Services\AssuntoService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Unit\Concerns\MakesQueryExceptions;
use Tests\Unit\Concerns\UsesInMemoryDatabase;

class AssuntoServiceTest extends TestCase
{
    use MakesQueryExceptions;
    use UsesInMemoryDatabase;

    #[Test]
    public function all_delega_para_o_repositorio(): void
    {
        $repository = Mockery::mock(AssuntoRepositoryInterface::class);
        $assuntos = new Collection([new Assunto(['codas' => 1, 'descricao' => 'Romance'])]);

        $repository->shouldReceive('all')->once()->andReturn($assuntos);

        $service = new AssuntoService($repository);

        $this->assertSame($assuntos, $service->all());
    }

    #[Test]
    public function create_delega_para_o_repositorio_com_os_dados_recebidos(): void
    {
        $repository = Mockery::mock(AssuntoRepositoryInterface::class);
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Ficção']);

        $repository->shouldReceive('create')
            ->once()
            ->with(['descricao' => 'Ficção'])
            ->andReturn($assunto);

        $service = new AssuntoService($repository);

        $this->assertSame($assunto, $service->create(['descricao' => 'Ficção']));
    }

    #[Test]
    public function update_delega_para_o_repositorio(): void
    {
        $repository = Mockery::mock(AssuntoRepositoryInterface::class);
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Antigo']);

        $repository->shouldReceive('update')
            ->once()
            ->with($assunto, ['descricao' => 'Novo'])
            ->andReturn($assunto);

        $service = new AssuntoService($repository);

        $this->assertSame($assunto, $service->update($assunto, ['descricao' => 'Novo']));
    }

    #[Test]
    public function delete_delega_a_exclusao_para_o_repositorio(): void
    {
        $repository = Mockery::mock(AssuntoRepositoryInterface::class);
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Romance']);

        $repository->shouldReceive('delete')->once()->with($assunto);

        $service = new AssuntoService($repository);

        $service->delete($assunto);

        $repository->shouldHaveReceived('delete')->once();
    }

    #[Test]
    public function delete_traduz_violacao_de_fk_em_registro_vinculado_exception(): void
    {
        $repository = Mockery::mock(AssuntoRepositoryInterface::class);
        $assunto = new Assunto(['codas' => 1, 'descricao' => 'Romance']);

        $repository->shouldReceive('delete')
            ->once()
            ->with($assunto)
            ->andThrow($this->foreignKeyViolation());

        $service = new AssuntoService($repository);

        $this->expectException(RegistroVinculadoException::class);
        $this->expectExceptionMessage('Este assunto está vinculado a um ou mais livros.');

        $service->delete($assunto);
    }
}

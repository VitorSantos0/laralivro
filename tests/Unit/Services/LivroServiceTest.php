<?php

namespace Tests\Unit\Services;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use App\Repositories\AssuntoListInterface;
use App\Repositories\AutorListInterface;
use App\Repositories\LivroRepositoryInterface;
use App\Services\LivroService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Unit\Concerns\UsesInMemoryDatabase;

class LivroServiceTest extends TestCase
{
    use UsesInMemoryDatabase;

    private function service(
        ?LivroRepositoryInterface $livroRepository = null,
        ?AutorListInterface $autorRepository = null,
        ?AssuntoListInterface $assuntoRepository = null,
    ): LivroService {
        return new LivroService(
            $livroRepository ?? Mockery::mock(LivroRepositoryInterface::class),
            $autorRepository ?? Mockery::mock(AutorListInterface::class),
            $assuntoRepository ?? Mockery::mock(AssuntoListInterface::class),
        );
    }

    #[Test]
    public function create_cria_o_livro_e_sincroniza_autores_e_assuntos(): void
    {
        $dados = [
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora X',
            'edicao' => 1,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
            'autores' => [1, 2],
            'assuntos' => [3],
        ];

        $livro = new Livro($dados);

        $repository = Mockery::mock(LivroRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->with($dados)->andReturn($livro);
        $repository->shouldReceive('syncAutores')->once()->with($livro, [1, 2]);
        $repository->shouldReceive('syncAssuntos')->once()->with($livro, [3]);

        $service = $this->service($repository);

        $this->assertSame($livro, $service->create($dados));
    }

    #[Test]
    public function update_atualiza_o_livro_e_ressincroniza_autores_e_assuntos(): void
    {
        $livro = new Livro(['codl' => 1, 'titulo' => 'Titulo Antigo']);
        $dados = [
            'titulo' => 'Titulo Novo',
            'editora' => 'Editora X',
            'edicao' => 2,
            'ano_publicacao' => 2020,
            'valor' => 59.90,
            'autores' => [4],
            'assuntos' => [5],
        ];

        $repository = Mockery::mock(LivroRepositoryInterface::class);
        $repository->shouldReceive('update')->once()->with($livro, $dados)->andReturn($livro);
        $repository->shouldReceive('syncAutores')->once()->with($livro, [4]);
        $repository->shouldReceive('syncAssuntos')->once()->with($livro, [5]);

        $service = $this->service($repository);

        $this->assertSame($livro, $service->update($livro, $dados));
    }

    #[Test]
    public function delete_desvincula_autores_e_assuntos_antes_de_excluir(): void
    {
        $livro = new Livro(['codl' => 1, 'titulo' => 'Dom Casmurro']);

        $repository = Mockery::mock(LivroRepositoryInterface::class);
        $repository->shouldReceive('detachAutores')->once()->with($livro)->ordered();
        $repository->shouldReceive('detachAssuntos')->once()->with($livro)->ordered();
        $repository->shouldReceive('delete')->once()->with($livro)->ordered();

        $service = $this->service($repository);

        $service->delete($livro);

        $repository->shouldHaveReceived('delete')->once();
    }

    #[Test]
    public function form_options_combina_autores_e_assuntos_disponiveis(): void
    {
        $autores = new Collection([new Autor(['codau' => 1, 'nome' => 'Machado de Assis'])]);
        $assuntos = new Collection([new Assunto(['codas' => 1, 'descricao' => 'Romance'])]);

        $autorRepository = Mockery::mock(AutorListInterface::class);
        $autorRepository->shouldReceive('all')->once()->andReturn($autores);

        $assuntoRepository = Mockery::mock(AssuntoListInterface::class);
        $assuntoRepository->shouldReceive('all')->once()->andReturn($assuntos);

        $service = $this->service(autorRepository: $autorRepository, assuntoRepository: $assuntoRepository);

        $this->assertSame(['autores' => $autores, 'assuntos' => $assuntos], $service->formOptions());
    }
}

<?php

namespace Tests\Unit\Services;

use App\Repositories\RelatorioRepositoryInterface;
use App\Services\RelatorioService;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RelatorioServiceTest extends TestCase
{
    #[Test]
    public function livros_por_autor_agrupados_delega_a_consulta_ao_repositorio_e_agrupa_por_autor(): void
    {
        $repository = Mockery::mock(RelatorioRepositoryInterface::class);
        $linhas = new Collection([
            (object) ['autor' => 'Machado de Assis', 'livro' => 'Dom Casmurro'],
            (object) ['autor' => 'Machado de Assis', 'livro' => 'Quincas Borba'],
            (object) ['autor' => 'Clarice Lispector', 'livro' => 'A Hora da Estrela'],
        ]);

        $repository->shouldReceive('livrosPorAutor')->once()->andReturn($linhas);

        $service = new RelatorioService($repository);

        $agrupado = $service->livrosPorAutorAgrupados();

        $this->assertTrue($agrupado->has('Machado de Assis'));
        $this->assertTrue($agrupado->has('Clarice Lispector'));
        $this->assertCount(2, $agrupado->get('Machado de Assis'));
        $this->assertCount(1, $agrupado->get('Clarice Lispector'));
    }
}

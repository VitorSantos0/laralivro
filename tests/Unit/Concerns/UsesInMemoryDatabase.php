<?php

namespace Tests\Unit\Concerns;

/**
 * Aponta a conexão padrão para SQLite em memória, só para que
 * DB::transaction() tenha uma conexão para abrir/fechar. Nenhuma
 * query real é executada: todo acesso a dados é mockado.
 */
trait UsesInMemoryDatabase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
    }
}

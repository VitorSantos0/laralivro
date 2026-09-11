<?php

namespace Tests\Unit\Concerns;

use Illuminate\Database\QueryException;
use PDOException;
use ReflectionProperty;

trait MakesQueryExceptions
{
    private function foreignKeyViolation(): QueryException
    {
        $previous = new PDOException('insert or update on table violates foreign key constraint');

        // PDOException é uma classe interna: Closure::bind não alcança seu escopo,
        // então o código SQLSTATE precisa ser ajustado via Reflection.
        $code = new ReflectionProperty(PDOException::class, 'code');
        $code->setValue($previous, '23503');

        return new QueryException('pgsql', 'delete from "autor" where "codau" = ?', [1], $previous);
    }
}

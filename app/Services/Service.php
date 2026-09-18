<?php

namespace App\Services;

use App\Exceptions\RegistroVinculadoException;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

use App\Enums\PostgresErrorCode;

abstract class Service
{
    /**
     * Executa a operação em uma transação, traduzindo violação de FK do
     * Postgres (23503) em RegistroVinculadoException quando aplicável.
     */
    protected function transactional(Closure $callback, ?string $conflictMessage = null): mixed
    {
        try {
            return DB::transaction($callback);
        } catch (QueryException $e) {
            if ($conflictMessage !== null && in_array($e->getCode(), PostgresErrorCode::violacoesDeVinculo())) {
                throw new RegistroVinculadoException($conflictMessage);
            }
            throw $e;
        }
    }
}

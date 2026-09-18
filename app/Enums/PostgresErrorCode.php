<?php

namespace App\Enums;

enum PostgresErrorCode: string
{
    case ForeignKeyViolation = '23503';
    case RestrictViolation = '23001';

    /**
     * Códigos que indicam tentativa de excluir/alterar um registro
     * ainda referenciado por outra tabela.
     */
    public static function violacoesDeVinculo(): array
    {
        return [
            self::ForeignKeyViolation->value,
            self::RestrictViolation->value,
        ];
    }
}
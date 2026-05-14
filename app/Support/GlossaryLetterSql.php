<?php

namespace App\Support;

use Illuminate\Database\ConnectionInterface;

/**
 * Portable SQL for "first character, uppercased" of a glossary term column.
 */
final class GlossaryLetterSql
{
    public static function firstUpperCharExpression(ConnectionInterface $connection, string $column = 'term'): string
    {
        return match ($connection->getDriverName()) {
            'pgsql' => "LEFT(UPPER({$column}), 1)",
            'mysql', 'mariadb' => "SUBSTRING(UPPER({$column}), 1, 1)",
            default => "SUBSTR(UPPER({$column}), 1, 1)",
        };
    }
}

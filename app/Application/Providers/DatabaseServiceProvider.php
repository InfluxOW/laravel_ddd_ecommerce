<?php

namespace App\Application\Providers;

use App\Infrastructure\Database\Connections\PostgresConnection;
use Closure;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;
use PDO;

final class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Connection::resolverFor('pgsql', static fn (PDO|Closure $pdo, string $database, string $prefix, array $config) => new PostgresConnection($pdo, $database, $prefix, $config));
    }
}

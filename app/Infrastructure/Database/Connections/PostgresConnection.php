<?php

namespace App\Infrastructure\Database\Connections;

use App\Infrastructure\Database\Builder;
use MStaack\LaravelPostgis\PostgisConnection;

final class PostgresConnection extends PostgisConnection
{
    public function query()
    {
        return new Builder($this, $this->getQueryGrammar(), $this->getPostProcessor());
    }
}

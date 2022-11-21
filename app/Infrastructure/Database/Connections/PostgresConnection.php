<?php

namespace App\Infrastructure\Database\Connections;

use App\Infrastructure\Database\Builder;
use Illuminate\Database\Query\Builder as BaseQuery;
use MStaack\LaravelPostgis\PostgisConnection;

final class PostgresConnection extends PostgisConnection
{
    public function query(): BaseQuery
    {
        return new Builder($this, $this->getQueryGrammar(), $this->getPostProcessor());
    }
}

<?php

namespace App\Infrastructure\Database;

use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Facades\Cache;
use JsonException;

final class Builder extends BaseBuilder
{
    protected function runSelect(): array
    {
        if (app()->runningInConsole()) {
            return parent::runSelect();
        }

        return Cache::rememberInArray($this->getCacheKey(), fn (): array => parent::runSelect());
    }

    /**
     * Returns a unique string that can identify this query.
     *
     * @throws JsonException
     */
    protected function getCacheKey(): string
    {
        $query = str_replace('?', '"%s"', $this->toSql());
        $query = vsprintf($query, $this->getBindings());

        return json_encode(['query' => hash('sha256', $query)], JSON_THROW_ON_ERROR);
    }
}

<?php

namespace App\Domains\Generic\Traits\Models;

use Illuminate\Support\Facades\Schema;

trait HasExtendedFunctionality
{
    use HasSimpleCache;

    protected string $columnsCacheKey = 'COLUMNS';

    public function getRawAttributes(array $except = []): array
    {
        $attributes = [];
        foreach ($this->getColumns() as $column) {
            if (in_array($column, $except, true)) {
                continue;
            }

            $attributes[$column] = $this->attributes[$column];
        }

        return $attributes;
    }

    private function getColumns(): array
    {
        return $this->cache->getOrSet($this->columnsCacheKey, fn (): array => Schema::getColumnListing($this->getTable()));
    }
}

<?php

namespace App\Domains\Generic\Traits\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait HasExtendedFunctionality
{
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

    public function getColumns(): array
    {
        $table = $this->getTable();

        return Cache::rememberInArray("{$table}_columns", static fn (): array => Schema::getColumnListing($table));
    }
}

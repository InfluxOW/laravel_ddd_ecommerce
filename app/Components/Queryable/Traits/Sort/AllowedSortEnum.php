<?php

namespace App\Components\Queryable\Traits\Sort;

trait AllowedSortEnum
{
    public function getDatabaseColumn(): string
    {
        return str($this->name)->replaceLast(static::getSortDirectionPostfix(), '')->lower()->value();
    }

    public function isDescending(): bool
    {
        return str_ends_with($this->name, static::getSortDirectionPostfix());
    }

    protected static function getSortDirectionPostfix(): string
    {
        return '_DESC';
    }
}

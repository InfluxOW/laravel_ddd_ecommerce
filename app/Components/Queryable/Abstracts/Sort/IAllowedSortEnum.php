<?php

namespace App\Components\Queryable\Abstracts\Sort;

interface IAllowedSortEnum
{
    public function getDatabaseColumn(): string;

    public function isDescending(): bool;
}

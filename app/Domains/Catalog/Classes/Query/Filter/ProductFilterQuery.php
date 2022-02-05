<?php

namespace App\Domains\Catalog\Classes\Query\Filter;

use App\Components\Queryable\Classes\Filter\Filter;
use Illuminate\Support\Collection;

final class ProductFilterQuery
{
    /**
     * @param Collection<Filter> $allowedFilters
     * @param Collection<Filter> $appliedFilters
     */
    public function __construct(public readonly Collection $allowedFilters, public readonly Collection $appliedFilters)
    {
    }
}

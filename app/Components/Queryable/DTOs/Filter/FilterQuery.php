<?php

namespace App\Components\Queryable\DTOs\Filter;

use App\Components\Queryable\Classes\Filter\Filter;
use Illuminate\Support\Collection;

final class FilterQuery
{
    /**
     * @param Collection<Filter> $allowedFilters
     * @param Collection<Filter> $appliedFilters
     */
    public function __construct(public readonly Collection $allowedFilters, public readonly Collection $appliedFilters)
    {
    }
}

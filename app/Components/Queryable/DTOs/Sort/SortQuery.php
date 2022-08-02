<?php

namespace App\Components\Queryable\DTOs\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use Illuminate\Support\Collection;

final class SortQuery
{
    /**
     * @param Collection<Sort> $allowedSorts
     * @param Sort             $appliedSort
     */
    public function __construct(public readonly Collection $allowedSorts, public readonly Sort $appliedSort)
    {
    }
}

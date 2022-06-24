<?php

namespace App\Domains\Catalog\Classes\Query\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use Illuminate\Support\Collection;

final class ProductSortQuery
{
    /**
     * @param Collection<Sort> $allowedSorts
     * @param Sort             $appliedSort
     */
    public function __construct(public readonly Collection $allowedSorts, public readonly Sort $appliedSort)
    {
    }
}

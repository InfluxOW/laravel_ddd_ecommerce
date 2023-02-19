<?php

namespace App\Components\Queryable\DTOs\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use Illuminate\Support\Collection;

final readonly class SortQuery
{
    /**
     * @param Collection<Sort> $allowedSorts
     */
    public function __construct(public Collection $allowedSorts, public Sort $appliedSort)
    {
    }
}

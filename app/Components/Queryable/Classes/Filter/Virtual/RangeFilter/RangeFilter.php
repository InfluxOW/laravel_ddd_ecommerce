<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\RangeFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class RangeFilter extends Filter
{
    /**
     * @OA\Property(enum={"RANGE"})
     */
    public string $type;

    /**
     * @OA\Property()
     *
     * @example 5.0
     */
    public int|float|null $min = null;

    /**
     * @OA\Property()
     *
     * @example 50.0
     */
    public int|float|null $max = null;
}

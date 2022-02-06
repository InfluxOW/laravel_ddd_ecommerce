<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\RangeFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class RangeFilter extends Filter
{
    /**
     * @OA\Property(enum={"RANGE"})
     * @var string
     */
    public $type;

    /**
     * @OA\Property()
     * @var float|null
     * @example 5.0
     */
    public $min_value;

    /**
     * @OA\Property()
     * @var float|null
     * @example 50.0
     */
    public $max_value;
}

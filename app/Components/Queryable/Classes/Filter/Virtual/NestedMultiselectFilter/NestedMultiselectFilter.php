<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\NestedMultiselectFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class NestedMultiselectFilter extends Filter
{
    /**
     * @OA\Property(enum={"NESTED_MULTISELECT"})
     *
     * @var string
     */
    public $type;
}

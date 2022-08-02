<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\PlainMultiselectFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class PlainMultiselectFilter extends Filter
{
    /**
     * @OA\Property(enum={"PLAIN_MULTISELECT"})
     *
     * @var string
     */
    public $type;
}

<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class MultiselectFilter extends Filter
{
    /**
     * @OA\Property(enum={"MULTISELECT"})
     *
     * @var string
     */
    public $type;
}

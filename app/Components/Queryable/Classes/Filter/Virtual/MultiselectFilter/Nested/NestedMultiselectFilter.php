<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Nested;

use App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\MultiselectFilter;
use OpenApi\Annotations as OA;

abstract class NestedMultiselectFilter extends MultiselectFilter
{
    /**
     * @OA\Property(enum={true})
     * @var bool
     */
    public $is_nested;
}

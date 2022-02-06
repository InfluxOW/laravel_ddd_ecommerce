<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Plain;

use App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\MultiselectFilter;
use OpenApi\Annotations as OA;

abstract class PlainMultiselectFilter extends MultiselectFilter
{
    /**
     * @OA\Property(enum={false})
     * @var bool
     */
    public $is_nested;
}

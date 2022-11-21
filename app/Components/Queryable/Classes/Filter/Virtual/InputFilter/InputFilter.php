<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\InputFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class InputFilter extends Filter
{
    /**
     * @OA\Property(enum={"INPUT"})
     */
    public string $type;
}

<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\SelectFilter;

use App\Components\Queryable\Classes\Filter\Virtual\Filter;
use OpenApi\Annotations as OA;

abstract class SelectFilter extends Filter
{
    /**
     * @OA\Property(enum={"SELECT"})
     * @var string
     */
    public $type;
}

<?php

namespace App\Components\Queryable\Classes\Filter\Virtual;

use OpenApi\Annotations as OA;

abstract class Filter
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $query;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $title;
}

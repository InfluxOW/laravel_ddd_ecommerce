<?php

namespace App\Components\Queryable\Classes\Filter\Virtual;

use OpenApi\Annotations as OA;

abstract class Filter
{
    /**
     * @OA\Property()
     */
    public string $query;

    /**
     * @OA\Property()
     */
    public string $title;
}

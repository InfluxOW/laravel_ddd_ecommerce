<?php

namespace App\Components\Queryable\Classes\Filter\Virtual;

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

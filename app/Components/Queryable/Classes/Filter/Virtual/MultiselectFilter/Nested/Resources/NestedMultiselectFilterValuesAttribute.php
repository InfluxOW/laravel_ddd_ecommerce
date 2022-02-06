<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Nested\Resources;

use App\Domains\Generic\Enums\Response\ResponseValueType;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class NestedMultiselectFilterValuesAttribute
{
    /**
     * @OA\Property()
     * @var string
     * @example Simple Attribute
     */
    public $title;

    /**
     * @OA\Property()
     * @var string
     * @example simple-attribute
     */
    public $query;

    /**
     * @OA\Property(ref="#/components/schemas/ResponseValueType")
     * @var ResponseValueType
     */
    public $values_type;
}

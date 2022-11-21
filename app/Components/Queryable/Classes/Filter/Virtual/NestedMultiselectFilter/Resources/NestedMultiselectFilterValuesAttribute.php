<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\NestedMultiselectFilter\Resources;

use App\Domains\Generic\Enums\Response\ResponseValueType;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class NestedMultiselectFilterValuesAttribute
{
    /**
     * @OA\Property()
     *
     * @example Simple Attribute
     */
    public string $title;

    /**
     * @OA\Property()
     *
     * @example simple-attribute
     */
    public string $query;

    /**
     * @OA\Property(ref="#/components/schemas/ResponseValueType")
     */
    public ResponseValueType $values_type;
}

<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Nested\Resources;

use App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Nested\Resources\NestedMultiselectFilterValuesAttribute;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class NestedMultiselectFilterValues
{
    /**
     * @OA\Property(ref="#/components/schemas/NestedMultiselectFilterValuesAttribute")
     * @var NestedMultiselectFilterValuesAttribute
     */
    public $attribute;

    /**
     * @OA\Property(collectionFormat="multi", @OA\Items(type="object", @OA\AdditionalProperties(oneOf={
     *    @OA\Schema(type="string"),
     *    @OA\Schema(type="integer"),
     *    @OA\Schema(type="boolean"),
     *    @OA\Schema(type="float"),
     * })))
     * @var array
     */
    public $values;
}

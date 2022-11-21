<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\NestedMultiselectFilter\Resources;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class NestedMultiselectFilterValues
{
    /**
     * @OA\Property(ref="#/components/schemas/NestedMultiselectFilterValuesAttribute")
     */
    public NestedMultiselectFilterValuesAttribute $attribute;

    /**
     * @OA\Property(collectionFormat="multi", @OA\Items(type="object", @OA\AdditionalProperties(oneOf={
     *    @OA\Schema(type="string"),
     *    @OA\Schema(type="integer"),
     *    @OA\Schema(type="boolean"),
     *    @OA\Schema(type="float"),
     * })))
     */
    public array $values;
}

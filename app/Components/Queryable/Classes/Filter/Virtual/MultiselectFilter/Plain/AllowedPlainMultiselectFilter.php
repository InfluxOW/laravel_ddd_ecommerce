<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Plain;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class AllowedPlainMultiselectFilter extends PlainMultiselectFilter
{
    /**
     * @OA\Property(collectionFormat="multi", @OA\Items(type="object", @OA\AdditionalProperties(oneOf={
     *    @OA\Schema(type="string"),
     *    @OA\Schema(type="integer"),
     *    @OA\Schema(type="boolean"),
     *    @OA\Schema(type="float"),
     * })))
     * @var array
     */
    public $allowed_values;
}

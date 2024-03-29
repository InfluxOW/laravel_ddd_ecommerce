<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\PlainMultiselectFilter;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class AppliedPlainMultiselectFilter extends PlainMultiselectFilter
{
    /**
     * @OA\Property(collectionFormat="multi", @OA\Items(type="object", @OA\AdditionalProperties(oneOf={
     *
     *    @OA\Schema(type="string"),
     *    @OA\Schema(type="integer"),
     *    @OA\Schema(type="boolean"),
     *    @OA\Schema(type="float"),
     * })))
     */
    public array $selected;
}

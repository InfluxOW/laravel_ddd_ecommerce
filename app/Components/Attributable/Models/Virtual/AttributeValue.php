<?php

namespace App\Components\Attributable\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class AttributeValue
{
    /**
     * @OA\Property(oneOf={
     *    @OA\Schema(type="string"),
     *    @OA\Schema(type="integer"),
     *    @OA\Schema(type="boolean"),
     *    @OA\Schema(type="float"),
     * })
     *
     * @var string|int|bool|float
     *
     * @example 35.4
     */
    public $value;

    /**
     * @OA\Property(ref="#/components/schemas/Attribute")
     *
     * @var Attribute
     */
    public $attribute;
}

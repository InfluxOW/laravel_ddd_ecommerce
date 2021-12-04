<?php

namespace App\Domain\Products\Models\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="ProductAttributeValue")
 * )
 */
class ProductAttributeValue
{
    /**
     * @OA\Property()
     * @var ProductAttribute
     */
    public $attribute;

    /**
     * @OA\Property(oneOf={
     *   @OA\Schema(type="string"),
     *   @OA\Schema(type="integer"),
     *   @OA\Schema(type="boolean"),
     *   @OA\Schema(type="float"),
     * })
     * @var mixed
     * @example 35.4
     */
    public $value;
}

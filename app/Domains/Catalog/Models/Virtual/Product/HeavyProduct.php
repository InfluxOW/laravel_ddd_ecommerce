<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Domains\Catalog\Models\Virtual\ProductAttributeValue;
use App\Domains\Catalog\Models\Virtual\ProductCategory\MediumProductCategory;

/**
 * @OA\Schema(
 *    @OA\Xml(name="HeavyProduct")
 * )
 */
final class HeavyProduct extends LightProduct
{
    /**
     * @OA\Property()
     * @var MediumProductCategory[]
     */
    public $categories;

    /**
     * @OA\Property()
     * @var ProductAttributeValue[]
     */
    public $attributes;

    /**
     * @OA\Property()
     * @var string
     * @example Home video game console developed by Sony Computer Entertainment.
     */
    public $description;
}

<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Domains\Catalog\Models\Virtual\ProductCategory\LightProductCategory;

/**
 * @OA\Schema(
 *    @OA\Xml(name="LightProduct")
 * )
 */
class LightProduct extends Product
{
    /**
     * @OA\Property()
     * @var LightProductCategory[]
     */
    public $categories;
}

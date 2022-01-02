<?php

namespace App\Domain\Catalog\Models\Virtual;

/**
 * @OA\Schema(
 *    @OA\Xml(name="HeavyProductCategory")
 * )
 */
class HeavyProductCategory extends LightProductCategory
{
    /**
     * @OA\Property()
     * @var int
     * @example 555
     */
    public $products_count;

    /**
     * @OA\Property()
     * @var self[]
     */
    public $children;
}

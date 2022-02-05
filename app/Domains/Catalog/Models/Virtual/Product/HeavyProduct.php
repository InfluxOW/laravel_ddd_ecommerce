<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Components\Mediable\Models\Virtual\Media;
use App\Domains\Catalog\Models\Virtual\ProductAttributeValue;
use App\Domains\Catalog\Models\Virtual\ProductCategory\MediumProductCategory;

/**
 * @OA\Schema()
 */
final class HeavyProduct extends LightProduct
{
    /**
     * @OA\Property()
     * @var Media[]
     */
    public $images;

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

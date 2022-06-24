<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Components\Mediable\Models\Virtual\Media;
use App\Domains\Catalog\Models\Virtual\ProductAttributeValue;
use App\Domains\Catalog\Models\Virtual\ProductCategory\MediumProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class HeavyProduct extends LightProduct
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Media")
     * )
     *
     * @var Media[]
     */
    public $images;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/MediumProductCategory")
     * )
     *
     * @var MediumProductCategory[]
     */
    public $categories;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/ProductAttributeValue")
     * )
     *
     * @var ProductAttributeValue[]
     */
    public $attributes;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example Home video game console developed by Sony Computer Entertainment.
     */
    public $description;
}

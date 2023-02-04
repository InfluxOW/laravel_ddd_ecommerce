<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Components\Attributable\Models\Virtual\AttributeValue;
use App\Components\Mediable\Models\Virtual\Media;
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
     *
     *     @OA\Items(ref="#/components/schemas/Media")
     * )
     *
     * @var Media[]
     */
    public array $images;

    /**
     * @OA\Property(
     *     type="array",
     *
     *     @OA\Items(ref="#/components/schemas/MediumProductCategory")
     * )
     *
     * @var MediumProductCategory[]
     */
    public array $categories;

    /**
     * @OA\Property(
     *     type="array",
     *
     *     @OA\Items(ref="#/components/schemas/AttributeValue")
     * )
     *
     * @var AttributeValue[]
     */
    public array $attributes;

    /**
     * @OA\Property()
     *
     * @example Home video game console developed by Sony Computer Entertainment.
     */
    public string $description;
}

<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Components\Mediable\Models\Virtual\Media;
use App\Domains\Catalog\Models\Virtual\ProductCategory\LightProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class LightProduct extends Product
{
    /**
     * @OA\Property(ref="#/components/schemas/Media")
     */
    public Media $image;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/LightProductCategory")
     * )
     *
     * @var LightProductCategory[]
     */
    public array $categories;
}

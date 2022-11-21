<?php

namespace App\Domains\Catalog\Models\Virtual\ProductCategory;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class LightProductCategory
{
    /**
     * @OA\Property()
     *
     * @example electronics
     */
    public string $slug;

    /**
     * @OA\Property()
     *
     * @example Electronics
     */
    public string $title;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products?filter%5Bcategory%5D=et-perspiciatis-quaerat
     */
    public string $url;
}

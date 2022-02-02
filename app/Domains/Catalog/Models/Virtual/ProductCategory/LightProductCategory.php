<?php

namespace App\Domains\Catalog\Models\Virtual\ProductCategory;

/**
 * @OA\Schema(
 *    @OA\Xml(name="LightProductCategory")
 * )
 */
class LightProductCategory
{
    /**
     * @OA\Property()
     * @var string
     * @example electronics
     */
    public $slug;

    /**
     * @OA\Property()
     * @var string
     * @example Electronics
     */
    public $title;

    /**
     * @OA\Property()
     * @var string
     * @example http://localhost:8085/api/products?filter%5Bcategory%5D=et-perspiciatis-quaerat
     */
    public $url;
}

<?php

namespace App\Domain\Products\Models\Virtual;

/**
 * @OA\Schema(
 *    @OA\Xml(name="Product")
 * )
 */
class Product
{
    /**
     * @OA\Property()
     * @var string
     * @example Playstation 4 Pro
     */
    public $title;

    /**
     * @OA\Property()
     * @var string
     * @example playstation-4-pro
     */
    public $slug;

    /**
     * @OA\Property()
     * @var string
     * @example 20 Oct 2020 22:40:18
     */
    public $created_at;

    /**
     * @OA\Property()
     * @var float
     * @example 39.99
     */
    public $price;

    /**
     * @OA\Property()
     * @var float|null
     * @example 19.99
     */
    public $price_discounted;

    /**
     * @OA\Property()
     * @var LightProductCategory
     */
    public $category;

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

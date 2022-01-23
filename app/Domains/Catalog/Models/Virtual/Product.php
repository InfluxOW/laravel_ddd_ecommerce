<?php

namespace App\Domains\Catalog\Models\Virtual;

use App\Components\Purchasable\Models\Virtual\Currency;
use App\Components\Purchasable\Models\Virtual\Money;

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
     * @var Money
     */
    public $price;

    /**
     * @OA\Property()
     * @var Money|null
     */
    public $price_discounted;

    /**
     * @OA\Property()
     * @var Currency
     */
    public $currency;

    /**
     * @OA\Property()
     * @var LightProductCategory[]
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

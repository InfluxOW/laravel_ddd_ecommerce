<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Components\Purchasable\Models\Virtual\Currency;
use App\Components\Purchasable\Models\Virtual\Money;
use OpenApi\Annotations as OA;

abstract class Product
{
    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example playstation-4-pro
     */
    public $slug;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example Playstation 4 Pro
     */
    public $title;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example http://localhost:8085/api/products/libero-laboriosam-dolorum
     */
    public $url;

    /**
     * @OA\Property(format="date-time")
     *
     * @var string
     *
     * @example 2022-02-05T04:21:52+00:00
     */
    public $created_at;

    /**
     * @OA\Property(ref="#/components/schemas/Money")
     *
     * @var Money
     */
    public $price;

    /**
     * @OA\Property(ref="#/components/schemas/Money")
     *
     * @var Money|null
     */
    public $price_discounted;

    /**
     * @OA\Property(ref="#/components/schemas/Currency")
     *
     * @var Currency
     */
    public $currency;
}

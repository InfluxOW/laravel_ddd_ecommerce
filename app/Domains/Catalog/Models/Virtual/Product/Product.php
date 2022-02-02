<?php

namespace App\Domains\Catalog\Models\Virtual\Product;

use App\Components\Mediable\Models\Virtual\Media;
use App\Components\Purchasable\Models\Virtual\Currency;
use App\Components\Purchasable\Models\Virtual\Money;

abstract class Product
{
    /**
     * @OA\Property()
     * @var Media[]
     */
    public $media;

    /**
     * @OA\Property()
     * @var string
     * @example playstation-4-pro
     */
    public $slug;

    /**
     * @OA\Property()
     * @var string
     * @example Playstation 4 Pro
     */
    public $title;

    /**
     * @OA\Property()
     * @var string
     * @example http://localhost:8085/api/products/libero-laboriosam-dolorum
     */
    public $url;

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
}

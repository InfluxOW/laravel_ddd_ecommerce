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
     * @example playstation-4-pro
     */
    public string $slug;

    /**
     * @OA\Property()
     *
     * @example Playstation 4 Pro
     */
    public string $title;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products/libero-laboriosam-dolorum
     */
    public string $url;

    /**
     * @OA\Property(format="date-time")
     *
     * @example 2022-02-05T04:21:52+00:00
     */
    public string $created_at;

    /**
     * @OA\Property(ref="#/components/schemas/Money")
     */
    public Money $price;

    /**
     * @OA\Property(ref="#/components/schemas/Money")
     */
    public ?Money $price_discounted = null;

    /**
     * @OA\Property(ref="#/components/schemas/Currency")
     */
    public Currency $currency;
}

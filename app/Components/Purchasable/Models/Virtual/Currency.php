<?php

namespace App\Components\Purchasable\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class Currency
{
    /**
     * @OA\Property()
     *
     * @example US Dollar
     */
    public string $name;

    /**
     * @OA\Property()
     *
     * @example USD
     */
    public string $abbreviation;

    /**
     * @OA\Property()
     *
     * @example $
     */
    public string $symbol;
}

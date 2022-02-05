<?php

namespace App\Components\Purchasable\Models\Virtual;

/**
 * @OA\Schema()
 */
final class Currency
{
    /**
     * @OA\Property()
     * @var string
     * @example US Dollar
     */
    public $name;

    /**
     * @OA\Property()
     * @var string
     * @example USD
     */
    public $abbreviation;

    /**
     * @OA\Property()
     * @var string
     * @example $
     */
    public $symbol;
}

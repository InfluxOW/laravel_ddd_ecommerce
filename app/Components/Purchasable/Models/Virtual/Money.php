<?php

namespace App\Components\Purchasable\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class Money
{
    /**
     * @OA\Property()
     *
     * @example 48.08
     */
    public float $value;

    /**
     * @OA\Property()
     *
     * @example 4808
     */
    public float|int $amount;

    /**
     * @OA\Property()
     *
     * @example $48.08
     */
    public string $render;
}

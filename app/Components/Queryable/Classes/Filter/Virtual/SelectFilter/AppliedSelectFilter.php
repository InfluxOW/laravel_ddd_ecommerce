<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\SelectFilter;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class AppliedSelectFilter extends SelectFilter
{
    /**
     * @OA\Property(oneOf={
     *    @OA\Schema(type="string"),
     *    @OA\Schema(type="integer"),
     *    @OA\Schema(type="boolean"),
     *    @OA\Schema(type="float"),
     * })
     *
     * @example USD
     */
    public string|int|bool|float $selected;
}

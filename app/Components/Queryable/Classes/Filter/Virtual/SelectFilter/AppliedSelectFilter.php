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
     * @var string|int|bool|float
     * @example USD
     */
    public $selected_value;
}

<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\InputFilter;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class AppliedInputFilter extends InputFilter
{
    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example test
     */
    public $selected_value;
}

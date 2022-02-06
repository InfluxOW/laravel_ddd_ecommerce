<?php

namespace App\Components\Queryable\Classes\Filter\Virtual\MultiselectFilter\Nested;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class AllowedNestedMultiselectFilter extends NestedMultiselectFilter
{
    /**
     * @OA\Property(collectionFormat="multi", @OA\Items(type="object", ref="#/components/schemas/NestedMultiselectFilterValues"))
     * @var array
     */
    public $allowed_values;
}

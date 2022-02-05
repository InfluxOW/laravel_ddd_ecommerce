<?php

namespace App\Domains\Catalog\Models\Virtual;

/**
 * @OA\Schema()
 */
final class ProductAttribute
{
    /**
     * @OA\Property()
     * @var string
     * @example Width
     */
    public $title;

    /**
     * @OA\Property()
     * @var string
     * @example width
     */
    public $slug;

    /**
     * @OA\Property(enum={"string", "boolean", "integer", "float"})
     * @var string
     * @example float
     */
    public $type;
}

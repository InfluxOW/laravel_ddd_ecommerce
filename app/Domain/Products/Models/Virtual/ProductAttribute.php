<?php

namespace App\Domain\Products\Models\Virtual;

/**
 * @OA\Schema(
 *    @OA\Xml(name="ProductAttribute")
 * )
 */
class ProductAttribute
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

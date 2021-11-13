<?php

namespace App\Models\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="ProductCategory")
 * )
 */
class ProductCategory
{
    /**
     * @OA\Property()
     * @var string
     * @example Electronics
     */
    public $title;

    /**
     * @OA\Property()
     * @var string
     * @example electronics
     */
    public $slug;

    /**
     * @OA\Property()
     * @var self[]
     */
    public $children;
}

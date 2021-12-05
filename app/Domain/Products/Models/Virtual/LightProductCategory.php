<?php

namespace App\Domain\Products\Models\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="LightProductCategory")
 * )
 */
class LightProductCategory
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
}
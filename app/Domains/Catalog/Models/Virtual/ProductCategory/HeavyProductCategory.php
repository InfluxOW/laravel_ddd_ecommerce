<?php

namespace App\Domains\Catalog\Models\Virtual\ProductCategory;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class HeavyProductCategory extends LightProductCategory
{
    /**
     * @OA\Property()
     * @var string
     * @example Consumer electronics are products used in a domestic or personal context, in contrast to items used for business, industrial, or professional recording purposes. These can include television sets, video players and recorders (VHS, DVD, Blu-ray), videocams, audio equipment, mobile telephones and pagers, portable devices and computers and related devices.
     */
    public $description;

    /**
     * @OA\Property()
     * @var int
     * @example 555
     */
    public $products_count;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/HeavyProductCategory")
     * )
     * @var self[]
     */
    public $children;
}

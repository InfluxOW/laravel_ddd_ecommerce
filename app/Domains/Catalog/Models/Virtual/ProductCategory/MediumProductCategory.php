<?php

namespace App\Domains\Catalog\Models\Virtual\ProductCategory;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class MediumProductCategory extends LightProductCategory
{
    /**
     * @OA\Property(ref="#/components/schemas/MediumProductCategory")
     */
    public self $parent;

    /**
     * @OA\Property()
     *
     * @example Consumer electronics are products used in a domestic or personal context, in contrast to items used for business, industrial, or professional recording purposes. These can include television sets, video players and recorders (VHS, DVD, Blu-ray), videocams, audio equipment, mobile telephones and pagers, portable devices and computers and related devices.
     */
    public string $description;
}

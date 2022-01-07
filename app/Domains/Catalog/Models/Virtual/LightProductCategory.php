<?php

namespace App\Domains\Catalog\Models\Virtual;

/**
 * @OA\Schema(
 *    @OA\Xml(name="LightProductCategory")
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

    /**
     * @OA\Property()
     * @var string
     * @example Consumer electronics are products used in a domestic or personal context, in contrast to items used for business, industrial, or professional recording purposes. These can include television sets, video players and recorders (VHS, DVD, Blu-ray), videocams, audio equipment, mobile telephones and pagers, portable devices and computers and related devices.
     */
    public $description;
}

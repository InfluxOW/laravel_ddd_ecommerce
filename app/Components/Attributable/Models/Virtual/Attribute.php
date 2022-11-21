<?php

namespace App\Components\Attributable\Models\Virtual;

use App\Domains\Generic\Enums\Response\ResponseValueType;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class Attribute
{
    /**
     * @OA\Property()
     *
     * @example Width
     */
    public string $title;

    /**
     * @OA\Property()
     *
     * @example width
     */
    public string $slug;

    /**
     * @OA\Property(ref="#/components/schemas/ResponseValueType")
     */
    public ResponseValueType $type;
}

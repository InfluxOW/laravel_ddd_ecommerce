<?php

namespace App\Components\Mediable\Models\Virtual;

use App\Components\Mediable\Enums\MediaType;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class Media
{
    /**
     * @OA\Property(ref="#/components/schemas/MediaType")
     * @var MediaType
     */
    public $type;

    /**
     * @OA\Property()
     * @var string
     * @example https://project.s3.eu-west-3.amazonaws.com/products/6-sed-maiores-provident/20/384512.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6C45DSHCBN3HI%2F20220201%2Feu-west-3%2Fs3%2Faws4_request&X-Amz-Date=20220201T111340Z&X-Amz-SignedHeaders=host&X-Amz-Expires=600&X-Amz-Signature=4bf67cfb4da346677061db4eae61b79de4bfc633488cd2731134
     */
    public $url;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/ResponsiveImage")
     * )
     * @var ResponsiveImage[]
     */
    public $responsive_images;
}

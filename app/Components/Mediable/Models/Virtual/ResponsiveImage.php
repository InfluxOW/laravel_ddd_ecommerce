<?php

namespace App\Components\Mediable\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class ResponsiveImage
{
    /**
     * @OA\Property()
     * @var int
     * @example 1920
     */
    public $width;

    /**
     * @OA\Property()
     * @var int
     * @example 1080
     */
    public $height;

    /**
     * @OA\Property()
     * @var string
     * @example https://project.s3.eu-west-3.amazonaws.com/products/6-sed-maiores-provident/19/photo_2021-04-26_12-07-33.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6CI34TSDGHZ43HI%2F20220201%2Feu-west-3%2Fs3%2Faws4_request&X-Amz-Date=20220201T111340Z&X-Amz-SignedHeaders=host&X-Amz-Expires=600&X-Amz-Signature=e28fc5c5d8826e5128d029346346acffacc64255b74b63d7bfe908
     */
    public $url;
}

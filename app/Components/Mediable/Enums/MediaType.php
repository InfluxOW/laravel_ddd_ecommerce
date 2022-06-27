<?php

namespace App\Components\Mediable\Enums;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
enum MediaType: string
{
    case IMAGE = 'image';
    case PDF = 'pdf';
    case SVG = 'svg';
    case VIDEO = 'video';
    case WEBP = 'webp';
    case OTHER = 'other';
}

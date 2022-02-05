<?php

namespace App\Components\Mediable\Enums;

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

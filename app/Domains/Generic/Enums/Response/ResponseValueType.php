<?php

namespace App\Domains\Generic\Enums\Response;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
enum ResponseValueType
{
    case INTEGER;
    case FLOAT;
    case STRING;
    case BOOLEAN;
}

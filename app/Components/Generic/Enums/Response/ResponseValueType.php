<?php

namespace App\Components\Generic\Enums\Response;

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

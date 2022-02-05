<?php

namespace App\Components\Generic\Enums\Response;

/**
 * @OA\Schema()
 */
enum ResponseValueType: string
{
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
}

<?php

namespace App\Components\Generic\Enums\Response;

enum ResponseValueType: string
{
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
}
<?php

namespace App\Domain\Generic\Response\Enums;

enum ResponseValueType: string
{
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
}

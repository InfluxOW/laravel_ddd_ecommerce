<?php

namespace App\Domain\Generic\Query\Enums;

enum AttributeValuesType: string
{
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
}

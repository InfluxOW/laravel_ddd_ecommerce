<?php

namespace App\Domain\Products\Enums;

enum ProductAttributeType: int
{
    case INTEGER = 0;
    case FLOAT = 1;
    case STRING = 2;
    case BOOLEAN = 3;
}

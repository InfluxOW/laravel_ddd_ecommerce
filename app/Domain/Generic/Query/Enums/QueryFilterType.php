<?php

namespace App\Domain\Generic\Query\Enums;

enum QueryFilterType: string
{
    case INPUT = 'input';
    case RANGE = 'range';
    case MULTISELECT = 'multiselect';
}

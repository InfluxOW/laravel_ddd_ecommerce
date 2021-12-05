<?php

namespace App\Domain\Generic\Query\Enums;

enum FrontendFilterType: string
{
    case INPUT = 'input';
    case RANGE = 'range';
    case MULTISELECT = 'multiselect';
}

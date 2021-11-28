<?php

namespace App\Domain\Products\Enums;

enum FrontendFilterType: string
{
    case INPUT = 'input';
    case RANGE = 'range';
    case MULTISELECT = 'multiselect';
}

<?php

namespace App\Domain\Products\Enums\Filters;

enum FrontendFilterType: string
{
    case INPUT = 'input';
    case RANGE = 'range';
    case MULTISELECT = 'multiselect';
}

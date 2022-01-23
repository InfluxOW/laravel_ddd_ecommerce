<?php

namespace App\Components\Queryable\Enums;

enum QueryFilterType: string
{
    case INPUT = 'input';
    case RANGE = 'range';
    case SELECT = 'select';
    case MULTISELECT = 'multiselect';
}

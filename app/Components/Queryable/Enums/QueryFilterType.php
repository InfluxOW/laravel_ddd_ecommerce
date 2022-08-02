<?php

namespace App\Components\Queryable\Enums;

enum QueryFilterType
{
    case INPUT;
    case RANGE;
    case SELECT;
    case NESTED_MULTISELECT;
    case PLAIN_MULTISELECT;
}

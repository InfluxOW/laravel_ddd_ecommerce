<?php

namespace App\Components\Queryable\Enums;

enum QueryFilterType
{
    case INPUT;
    case RANGE;
    case SELECT;
    case MULTISELECT;
}

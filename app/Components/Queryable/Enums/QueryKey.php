<?php

namespace App\Components\Queryable\Enums;

enum QueryKey: string
{
    case SORT = 'sort';
    case FILTER = 'filter';
    case INCLUDE = 'include';
    case FIELDS = 'fields';
    case APPEND = 'append';
    case PAGE = 'page';
    case PER_PAGE = 'per_page';
}

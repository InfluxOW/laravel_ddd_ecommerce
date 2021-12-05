<?php

namespace App\Domain\Generic\Query\Enums;

enum QueryKey: string
{
    case SORT = 'sort';
    case FILTER = 'filter';
}

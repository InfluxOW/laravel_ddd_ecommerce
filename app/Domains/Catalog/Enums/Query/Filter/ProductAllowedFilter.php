<?php

namespace App\Domains\Catalog\Enums\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\AllowedFilterEnum;

enum ProductAllowedFilter implements AllowedFilterEnum
{
    case SEARCH;
    case CATEGORY;
    case PRICE_BETWEEN;
    case ATTRIBUTE_VALUE;
    case CURRENCY;
}

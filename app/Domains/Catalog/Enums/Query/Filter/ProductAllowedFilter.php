<?php

namespace App\Domains\Catalog\Enums\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\IAllowedFilterEnum;

enum ProductAllowedFilter implements IAllowedFilterEnum
{
    case SEARCH;
    case CATEGORY;
    case PRICE_BETWEEN;
    case ATTRIBUTE_VALUE;
    case CURRENCY;
}

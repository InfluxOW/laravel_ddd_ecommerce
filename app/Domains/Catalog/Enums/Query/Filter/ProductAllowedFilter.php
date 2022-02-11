<?php

namespace App\Domains\Catalog\Enums\Query\Filter;

enum ProductAllowedFilter
{
    case SEARCH;
    case CATEGORY;
    case PRICE_BETWEEN;
    case ATTRIBUTE_VALUE;
    case CURRENCY;
}

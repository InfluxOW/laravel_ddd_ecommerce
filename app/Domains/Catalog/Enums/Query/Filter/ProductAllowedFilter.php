<?php

namespace App\Domains\Catalog\Enums\Query\Filter;

enum ProductAllowedFilter: string
{
    case TITLE = 'title';
    case DESCRIPTION = 'description';
    case CATEGORY = 'category';
    case PRICE_BETWEEN = 'price_between';
    case ATTRIBUTE_VALUE = 'attribute';
    case CURRENCY = 'currency';
}

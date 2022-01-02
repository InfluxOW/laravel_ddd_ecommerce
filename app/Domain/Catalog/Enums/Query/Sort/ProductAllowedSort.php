<?php

namespace App\Domain\Catalog\Enums\Query\Sort;

enum ProductAllowedSort: string
{
    case TITLE = 'title';
    case CREATED_AT = 'created_at';
    case PRICE = 'price';
}

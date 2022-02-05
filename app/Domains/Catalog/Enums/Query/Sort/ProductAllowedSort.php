<?php

namespace App\Domains\Catalog\Enums\Query\Sort;

/**
 * @OA\Schema()
 */
enum ProductAllowedSort: string
{
    case TITLE = 'title';
    case PRICE = 'price';
    case CREATED_AT = 'created_at';

    case TITLE_DESC = '-title';
    case PRICE_DESC = '-price';
    case CREATED_AT_DESC = '-created_at';
}

<?php

namespace App\Domains\Catalog\Enums\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\IAllowedSortEnum;
use App\Components\Queryable\Traits\Sort\AllowedSortEnum;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
enum ProductAllowedSort implements IAllowedSortEnum
{
    use AllowedSortEnum;

    case DEFAULT;

    case TITLE;
    case PRICE;
    case CREATED_AT;

    case TITLE_DESC;
    case PRICE_DESC;
    case CREATED_AT_DESC;
}

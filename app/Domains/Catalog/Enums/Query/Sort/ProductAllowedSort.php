<?php

namespace App\Domains\Catalog\Enums\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\AllowedSortEnum;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
enum ProductAllowedSort implements AllowedSortEnum
{
    case DEFAULT;

    case TITLE;
    case PRICE;
    case CREATED_AT;

    case TITLE_DESC;
    case PRICE_DESC;
    case CREATED_AT_DESC;

    public function getDatabaseField(): string
    {
        return str($this->name)->replaceLast('_DESC', '')->lower()->value();
    }
}

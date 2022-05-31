<?php

namespace App\Domains\Catalog\Enums\Query\Sort;

/**
 * @OA\Schema()
 */
enum ProductAllowedSort
{
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

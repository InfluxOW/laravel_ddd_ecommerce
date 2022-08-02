<?php

namespace App\Domains\News\Enums\Query\Sort;

use App\Components\Queryable\Abstracts\Sort\IAllowedSortEnum;
use App\Components\Queryable\Traits\Sort\AllowedSortEnum;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
enum ArticleAllowedSort implements IAllowedSortEnum
{
    use AllowedSortEnum;

    case DEFAULT;

    case TITLE;
    case PUBLISHED_AT;

    case TITLE_DESC;
    case PUBLISHED_AT_DESC;
}

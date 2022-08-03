<?php

namespace App\Domains\News\Enums\Query\Filter;

use App\Components\Queryable\Abstracts\Filter\IAllowedFilterEnum;

enum ArticleAllowedFilter implements IAllowedFilterEnum
{
    case SEARCH;
    case PUBLISHED_BETWEEN;
}

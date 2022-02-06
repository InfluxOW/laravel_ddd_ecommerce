<?php

namespace App\Components\Queryable\Classes\Sort;

use App\Components\Queryable\Abstracts\Query;
use App\Domains\Generic\Utils\LangUtils;
use BackedEnum;

final class Sort extends Query
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function create(BackedEnum $sort): self
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($sort);

        return new self($sort->name, $translation);
    }
}

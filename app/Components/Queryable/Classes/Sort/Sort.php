<?php

namespace App\Components\Queryable\Classes\Sort;

use App\Components\Generic\Utils\EnumUtils;
use App\Components\Generic\Utils\LangUtils;
use App\Components\Queryable\Abstracts\Query;
use BackedEnum;

final class Sort extends Query
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function createAsc(BackedEnum $sort): self
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($sort);

        return new self((string) $sort->value, $translation);
    }

    public static function createDesc(BackedEnum $sort): self
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($sort, EnumUtils::descendingValue($sort));

        return new self(EnumUtils::descendingValue($sort), $translation);
    }
}
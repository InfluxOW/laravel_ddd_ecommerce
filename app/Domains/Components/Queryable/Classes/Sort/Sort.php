<?php

namespace App\Domains\Components\Queryable\Classes\Sort;

use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Components\Generic\Utils\EnumUtils;
use App\Domains\Components\Generic\Utils\LangUtils;
use App\Domains\Components\Queryable\Abstracts\Query;
use BackedEnum;

class Sort extends Query
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function createAsc(BackedEnum $sort, TranslationNamespace $namespace): self
    {
        return new self((string) $sort->value, LangUtils::translateEnum($namespace, $sort));
    }

    public static function createDesc(BackedEnum $sort, TranslationNamespace $namespace): self
    {
        return new self(EnumUtils::descendingValue($sort), LangUtils::translateEnum($namespace, $sort, EnumUtils::descendingValue($sort)));
    }
}

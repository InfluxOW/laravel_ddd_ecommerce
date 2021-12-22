<?php

namespace App\Domain\Generic\Query\Models\Sort;

use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Query\Abstracts\Query;
use App\Domain\Generic\Utils\EnumUtils;
use App\Domain\Generic\Utils\LangUtils;
use BackedEnum;

class Sort extends Query
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function createAsc(BackedEnum $sort, TranslationNamespace $namespace): self
    {
        return new self($sort->value, LangUtils::translateEnum($namespace, $sort));
    }

    public static function createDesc(BackedEnum $sort, TranslationNamespace $namespace): self
    {
        return new self(EnumUtils::descendingValue($sort), LangUtils::translateEnum($namespace, $sort, EnumUtils::descendingValue($sort)));
    }
}

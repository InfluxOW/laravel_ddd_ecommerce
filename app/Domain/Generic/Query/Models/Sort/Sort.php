<?php

namespace App\Domain\Generic\Query\Models\Sort;

use App\Domain\Generic\Query\Abstracts\Query;
use App\Domain\Generic\Utils\EnumUtils;
use App\Domain\Generic\Utils\LangUtils;
use BackedEnum;

class Sort extends Query
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function createAsc(BackedEnum $sort): self
    {
        return new self($sort->value, LangUtils::translateEnum($sort));
    }

    public static function createDesc(BackedEnum $sort): self
    {
        return new self(EnumUtils::descendingValue($sort), LangUtils::translateEnum($sort, EnumUtils::descendingValue($sort)));
    }
}

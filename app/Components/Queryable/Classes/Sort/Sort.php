<?php

namespace App\Components\Queryable\Classes\Sort;

use App\Components\Queryable\Abstracts\Query;
use App\Domains\Common\Utils\LangUtils;
use UnitEnum;

final class Sort extends Query
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function create(UnitEnum $sort): self
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($sort);

        return new self($sort->name, $translation);
    }
}

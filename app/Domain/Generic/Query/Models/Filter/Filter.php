<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Query\Abstracts\Query;
use App\Domain\Generic\Query\Enums\FrontendFilterType;
use App\Domain\Generic\Utils\LangUtils;
use BackedEnum;

abstract class Filter extends Query
{
    public static FrontendFilterType $type;

    public function __construct(BackedEnum $filter)
    {
        $this->query = $filter->value;
        $this->title = LangUtils::translateEnum($filter);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'type' => static::$type->value,
        ]);
    }

    abstract public function ofValues(mixed ...$values): ?static;
}

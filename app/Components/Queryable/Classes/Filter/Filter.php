<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Components\Generic\Utils\LangUtils;
use App\Components\Queryable\Abstracts\Query;
use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;
use JetBrains\PhpStorm\ArrayShape;

abstract class Filter extends Query
{
    public static QueryFilterType $type;

    public function __construct(BackedEnum $filter, TranslationNamespace $namespace)
    {
        $this->query = (string) $filter->value;
        $this->title = LangUtils::translateEnum($namespace, $filter);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'type' => static::$type->value,
        ]);
    }

    abstract public function ofValues(mixed ...$values): ?static;
}

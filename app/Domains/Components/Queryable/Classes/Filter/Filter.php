<?php

namespace App\Domains\Components\Queryable\Classes\Filter;

use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Components\Generic\Utils\LangUtils;
use App\Domains\Components\Queryable\Abstracts\Query;
use App\Domains\Components\Queryable\Enums\QueryFilterType;
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

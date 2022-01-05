<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Query\Abstracts\Query;
use App\Domain\Generic\Query\Enums\QueryFilterType;
use App\Domain\Generic\Utils\LangUtils;
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

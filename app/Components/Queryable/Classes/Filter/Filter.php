<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Components\Generic\Utils\LangUtils;
use App\Components\Queryable\Abstracts\Query;
use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;
use JetBrains\PhpStorm\ArrayShape;

abstract class Filter extends Query
{
    public static QueryFilterType $type;

    public function __construct(BackedEnum $filter, ServiceProviderNamespace $namespace)
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($namespace, $filter);

        $this->query = (string) $filter->value;
        $this->title = $translation;
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'type' => static::$type->value,
        ]);
    }

    abstract public function ofValues(mixed ...$values): ?self;
}

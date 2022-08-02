<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Abstracts\Query;
use App\Components\Queryable\Enums\QueryFilterType;
use App\Domains\Generic\Utils\LangUtils;
use JetBrains\PhpStorm\ArrayShape;
use UnitEnum;

abstract class Filter extends Query
{
    public static QueryFilterType $type;

    public function __construct(UnitEnum $filter)
    {
        /** @var string $translation */
        $translation = LangUtils::translateEnum($filter);

        $this->query = $filter->name;
        $this->title = $translation;
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string'])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'type' => static::$type->name,
        ]);
    }

    abstract public function allowed(): array;

    abstract public function applied(): array;

    abstract public function isset(): bool;
}

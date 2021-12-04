<?php

namespace App\Domain\Products\Models\Generic\Filters;

use App\Domain\Products\Enums\Filters\FrontendFilterType;
use App\Domain\Products\Enums\Filters\ProductAllowedFilter;
use App\Domain\Utils\LangUtils;

abstract class Filter
{
    public static FrontendFilterType $type;

    public string $query;
    public string $title;

    public function __construct(ProductAllowedFilter $filter)
    {
        $this->query = $filter->value;
        $this->title = LangUtils::translateEnum($filter);
    }

    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'title' => $this->title,
            'type' => static::$type->value,
        ];
    }

    abstract public function ofValues(mixed ...$values): static;
}

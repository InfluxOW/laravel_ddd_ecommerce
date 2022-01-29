<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

final class SelectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::SELECT;

    public function __construct(
        BackedEnum $filter,
        ServiceProviderNamespace $namespace,
        public Collection|EloquentCollection $values
    ) {
        parent::__construct($filter, $namespace);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'values' => "array"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'values' => $this->values->toArray(),
        ]);
    }

    public function ofValues(mixed ...$values): ?self
    {
        $filter = clone($this);
        $filter->values = $this->values->filter(fn (string $value): bool => in_array($value, $values, true));

        return $filter->values->isEmpty() ? null : $filter;
    }
}

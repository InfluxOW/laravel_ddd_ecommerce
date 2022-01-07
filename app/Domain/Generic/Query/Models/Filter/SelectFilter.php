<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Enums\Lang\TranslationNamespace;
use App\Domain\Generic\Query\Enums\QueryFilterType;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class SelectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::SELECT;

    public function __construct(
        BackedEnum $filter,
        TranslationNamespace $namespace,
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

    public function ofValues(mixed ...$values): ?static
    {
        $filter = clone($this);
        $filter->values = $this->values->filter(fn (string $value): bool => in_array($value, $values, true));

        return $filter->values->isEmpty() ? null : $filter;
    }
}

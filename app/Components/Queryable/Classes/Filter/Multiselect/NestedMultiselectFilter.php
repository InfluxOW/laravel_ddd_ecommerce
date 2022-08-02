<?php

namespace App\Components\Queryable\Classes\Filter\Multiselect;

use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Classes\Filter\Multiselect\Resources\NestedMultiselectFilterValues;
use App\Components\Queryable\Enums\QueryFilterType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use UnitEnum;

final class NestedMultiselectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::NESTED_MULTISELECT;

    private Collection|EloquentCollection $selected;

    /**
     * @param Collection<NestedMultiselectFilterValues>|EloquentCollection<NestedMultiselectFilterValues> $allowed
     */
    public function __construct(
        UnitEnum $filter,
        public Collection|EloquentCollection $allowed
    ) {
        parent::__construct($filter);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'allowed' => 'array'])]
    public function allowed(): array
    {
        return array_merge($this->toArray(), [
            'allowed' => $this->allowed->map(fn (NestedMultiselectFilterValues $values): array => $values->toArray())->toArray(),
        ]);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'selected' => 'array'])]
    public function applied(): array
    {
        return array_merge($this->toArray(), [
            'selected' => $this->selected->map(fn (NestedMultiselectFilterValues $values): array => $values->toArray())->toArray(),
        ]);
    }

    public function apply(array ...$values): self
    {
        $filter = clone $this;

        $selected = $filter->allowed
            ->filter(fn (NestedMultiselectFilterValues $attributeWithValues): bool => array_key_exists($attributeWithValues->attribute->query, $values))
            ->map(fn (NestedMultiselectFilterValues $attributeWithValues): NestedMultiselectFilterValues => new NestedMultiselectFilterValues(
                $attributeWithValues->attribute,
                collect($values[$attributeWithValues->attribute->query])->filter(fn (string $value): bool => $attributeWithValues->values->contains($attributeWithValues->adjustValueType($value)))
            ));

        $filter->selected = $selected->values();

        return $filter;
    }

    public function isset(): bool
    {
        return $this->selected->isNotEmpty();
    }
}

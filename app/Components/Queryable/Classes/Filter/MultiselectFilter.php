<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Classes\Filter\Resources\MultiselectFilter\NestedMultiselectFilterValues;
use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

final class MultiselectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::MULTISELECT;

    public Collection|EloquentCollection $selectedValues;

    protected function __construct(
        BackedEnum $filter,
        public readonly bool $isNested,
        public Collection|EloquentCollection $allowedValues
    ) {
        parent::__construct($filter);
    }

    /**
     * @param BackedEnum $filter
     * @param Collection<string>|EloquentCollection<string> $allowedValues
     *
     * @return self
     */
    public static function createWithPlainValues(BackedEnum $filter, Collection|EloquentCollection $allowedValues): self
    {
        return new self($filter, false, $allowedValues);
    }

    /**
     * @param BackedEnum $filter
     * @param Collection<NestedMultiselectFilterValues> $allowedValues
     *
     * @return self
     */
    public static function createWithNestedValues(BackedEnum $filter, Collection $allowedValues): self
    {
        return new self($filter, true, $allowedValues);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'is_nested' => "boolean", 'values' => "array"])]
    public function toAllowedArray(): array
    {
        return array_merge($this->toArray(), [
            'is_nested' => $this->isNested,
            'allowed_values' => $this->isNested ? $this->allowedValues->map(fn (NestedMultiselectFilterValues $values): array => $values->toArray())->toArray() : $this->allowedValues->toArray(),
        ]);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'is_nested' => "boolean", 'values' => "array"])]
    public function toAppliedArray(): array
    {
        return array_merge($this->toArray(), [
            'is_nested' => $this->isNested,
            'selected_values' => $this->isNested ? $this->selectedValues->map(fn (NestedMultiselectFilterValues $values): array => $values->toArray())->toArray() : $this->selectedValues->toArray(),
        ]);
    }

    public function setSelectedValues(string|int|bool|float|array|null ...$values): ?self
    {
        $filter = clone($this);

        $correctValues = match ($filter->isNested) {
            false => $this->allowedValues
                ->filter(fn (string $value): bool => in_array($value, $values, true)),
            true => $this->allowedValues
                ->filter(fn (NestedMultiselectFilterValues $attributeWithValues): bool => array_key_exists($attributeWithValues->attribute->query, $values))
                ->map(fn (NestedMultiselectFilterValues $attributeWithValues): NestedMultiselectFilterValues => new NestedMultiselectFilterValues(
                    $attributeWithValues->attribute,
                    collect($values[$attributeWithValues->attribute->query])->filter(fn (string $value): bool => $attributeWithValues->values->contains($attributeWithValues->adjustValueType($value)))
                ))
        };

        $filter->selectedValues = $correctValues->values();

        return $filter->selectedValues->isEmpty() ? null : $filter;
    }
}

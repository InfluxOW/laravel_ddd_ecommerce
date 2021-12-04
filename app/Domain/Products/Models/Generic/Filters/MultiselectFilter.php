<?php

namespace App\Domain\Products\Models\Generic\Filters;

use App\Domain\Products\Enums\Filters\FrontendFilterType;
use App\Domain\Products\Enums\Filters\MultiselectFilterValuesType;
use App\Domain\Products\Enums\Filters\ProductAllowedFilter;
use App\Domain\Products\Models\Generic\Filters\Resources\Multiselect\MultiselectFilterNestedValues;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class MultiselectFilter extends Filter
{
    public static FrontendFilterType $type = FrontendFilterType::MULTISELECT;

    public function __construct(
        ProductAllowedFilter $filter,
        public readonly MultiselectFilterValuesType $valuesType,
        public Collection|EloquentCollection $values
    ) {
        parent::__construct($filter);
    }

    /**
     * @param \App\Domain\Products\Enums\Filters\ProductAllowedFilter $filter
     * @param \Illuminate\Support\Collection<string>|\Illuminate\Database\Eloquent\Collection<string> $values
     * @return self
     */
    public static function createWithPlainValues(ProductAllowedFilter $filter, Collection|EloquentCollection $values): self
    {
        return new self($filter, MultiselectFilterValuesType::PLAIN, $values);
    }

    /**
     * @param \App\Domain\Products\Enums\Filters\ProductAllowedFilter $filter
     * @param \Illuminate\Support\Collection<MultiselectFilterNestedValues> $values
     * @return self
     */
    public static function createWithNestedValues(ProductAllowedFilter $filter, Collection $values): self
    {
        return new self($filter, MultiselectFilterValuesType::NESTED, $values);
    }

    public function toArray(): array
    {
        $values = match ($this->valuesType) {
            MultiselectFilterValuesType::PLAIN => $this->values->toArray(),
            MultiselectFilterValuesType::NESTED => $this->values->map->toArray()->toArray(),
        };

        return array_merge(parent::toArray(), [
            'values_type' => $this->valuesType->value,
            'values' => $values,
        ]);
    }

    public function ofValues(mixed ...$values): static
    {
        $filter = clone($this);

        $correctValues = collect([]);

        if ($filter->valuesType === MultiselectFilterValuesType::PLAIN) {
            $correctValues = $this->values->filter(fn (string $value) => in_array($value, $values, true))->values();
        }

        if ($filter->valuesType === MultiselectFilterValuesType::NESTED) {
            $correctValues = $this->values
                ->filter(function (MultiselectFilterNestedValues $attributeWithValues) use ($values): bool {
                    return array_key_exists($attributeWithValues->attribute->slug, $values);
                })
                ->map(function (MultiselectFilterNestedValues $attributeWithValues) use ($values): MultiselectFilterNestedValues {
                    $attributeWithValues->values = collect($values[$attributeWithValues->attribute->slug])
                        ->filter(function (string $value) use ($attributeWithValues): bool {
                            return $attributeWithValues->values->contains($value);
                        });

                    return $attributeWithValues;
                })
                ->values();
        }

        $filter->values = $correctValues;

        return $filter;
    }
}

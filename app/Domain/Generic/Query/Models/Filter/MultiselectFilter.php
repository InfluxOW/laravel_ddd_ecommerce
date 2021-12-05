<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Query\Enums\FrontendFilterType;
use App\Domain\Generic\Query\Models\Filter\Resources\Multiselect\MultiselectFilterNestedValues;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class MultiselectFilter extends Filter
{
    public static FrontendFilterType $type = FrontendFilterType::MULTISELECT;

    public function __construct(
        BackedEnum $filter,
        public readonly bool $isNested,
        public Collection|EloquentCollection $values
    ) {
        parent::__construct($filter);
    }

    /**
     * @param BackedEnum $filter
     * @param Collection<string>|EloquentCollection<string> $values
     * @return self
     */
    public static function createWithPlainValues(BackedEnum $filter, Collection|EloquentCollection $values): self
    {
        return new self($filter, false, $values);
    }

    /**
     * @param BackedEnum $filter
     * @param Collection<MultiselectFilterNestedValues> $values
     * @return self
     */
    public static function createWithNestedValues(BackedEnum $filter, Collection $values): self
    {
        return new self($filter, true, $values);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'is_nested' => $this->isNested,
            'values' => $this->isNested ? $this->values->map->toArray()->toArray() : $this->values->toArray(),
        ]);
    }

    public function ofValues(mixed ...$values): ?static
    {
        $filter = clone ($this);

        if ($filter->isNested) {
            $correctValues = $this->values
                ->filter(function (MultiselectFilterNestedValues $attributeWithValues) use ($values): bool {
                    return array_key_exists($attributeWithValues->attribute->query, $values);
                })
                ->map(function (MultiselectFilterNestedValues $attributeWithValues) use ($values): MultiselectFilterNestedValues {
                    $correctValues = collect($values[$attributeWithValues->attribute->query])
                        ->filter(function (string $value) use ($attributeWithValues): bool {
                            return $attributeWithValues->values->contains($value);
                        });

                    return new MultiselectFilterNestedValues($attributeWithValues->attribute, $correctValues);
                });
        } else {
            $correctValues = $this->values->filter(fn (string $value) => in_array($value, $values, true));
        }

        $filter->values = $correctValues->values();

        return $filter->values->isEmpty() ? null : $filter;
    }
}

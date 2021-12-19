<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Query\Enums\QueryFilterType;
use App\Domain\Generic\Query\Models\Filter\Resources\Multiselect\MultiselectFilterNestedValues;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class MultiselectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::MULTISELECT;

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

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'is_nested' => "boolean", 'values' => "array"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'is_nested' => $this->isNested,
            'values' => $this->isNested ? $this->values->map->toArray()->toArray() : $this->values->toArray(),
        ]);
    }

    public function ofValues(mixed ...$values): ?static
    {
        $filter = clone($this);

        $correctValues = match ($filter->isNested) {
            false => $this->values
                ->filter(fn (string $value): bool => in_array($value, $values, true)),
            true => $this->values
                ->filter(fn (MultiselectFilterNestedValues $attributeWithValues): bool => array_key_exists($attributeWithValues->attribute->query, $values))
                ->map(fn (MultiselectFilterNestedValues $attributeWithValues): MultiselectFilterNestedValues => new MultiselectFilterNestedValues(
                    $attributeWithValues->attribute,
                    collect($values[$attributeWithValues->attribute->query])->filter(fn (string $value): bool => $attributeWithValues->values->contains($value))
                ))
        };

        $filter->values = $correctValues->values();

        return $filter->values->isEmpty() ? null : $filter;
    }
}

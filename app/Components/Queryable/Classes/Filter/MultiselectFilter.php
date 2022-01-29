<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Components\Queryable\Classes\Filter\Resources\Multiselect\MultiselectFilterNestedValues;
use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

final class MultiselectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::MULTISELECT;

    protected function __construct(
        BackedEnum $filter,
        ServiceProviderNamespace $namespace,
        public readonly bool $isNested,
        public Collection|EloquentCollection $values
    ) {
        parent::__construct($filter, $namespace);
    }

    /**
     * @param BackedEnum $filter
     * @param ServiceProviderNamespace $namespace
     * @param Collection<string>|EloquentCollection<string> $values
     * @return self
     */
    public static function createWithPlainValues(BackedEnum $filter, ServiceProviderNamespace $namespace, Collection|EloquentCollection $values): self
    {
        return new self($filter, $namespace, false, $values);
    }

    /**
     * @param BackedEnum $filter
     * @param ServiceProviderNamespace $namespace
     * @param Collection<MultiselectFilterNestedValues> $values
     * @return self
     */
    public static function createWithNestedValues(BackedEnum $filter, ServiceProviderNamespace $namespace, Collection $values): self
    {
        return new self($filter, $namespace, true, $values);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'is_nested' => "boolean", 'values' => "array"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'is_nested' => $this->isNested,
            'values' => $this->isNested ? $this->values->map->toArray()->toArray() : $this->values->toArray(),
        ]);
    }

    public function ofValues(mixed ...$values): ?self
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

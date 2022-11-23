<?php

namespace App\Components\Queryable\Classes\Filter\Multiselect\Resources;

use App\Domains\Common\Enums\BooleanString;
use App\Domains\Common\Enums\Response\ResponseValueType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

final class NestedMultiselectFilterValues
{
    /**
     * @param Collection<string>|EloquentCollection<string> $values
     */
    public function __construct(public readonly NestedMultiselectFilterValuesAttribute $attribute, public readonly Collection|EloquentCollection $values)
    {
    }

    #[ArrayShape(['attribute' => 'array', 'values' => 'array'])]
    public function toArray(): array
    {
        return [
            'attribute' => $this->attribute->toArray(),
            'values' => $this->values->toArray(),
        ];
    }

    public function adjustValueType(string $value): string|int|bool|float
    {
        return match ($this->attribute->valuesType) {
            ResponseValueType::STRING => $value,
            ResponseValueType::INTEGER => (int) $value,
            ResponseValueType::FLOAT => (float) $value,
            ResponseValueType::BOOLEAN => ($value === BooleanString::_TRUE->value),
        };
    }
}

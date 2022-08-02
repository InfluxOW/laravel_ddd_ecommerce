<?php

namespace App\Components\Queryable\Classes\Filter;

use Akaunting\Money\Money;
use App\Components\Queryable\Enums\QueryFilterType;
use App\Domains\Generic\Utils\MathUtils;
use JetBrains\PhpStorm\ArrayShape;
use UnitEnum;

final class RangeFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::RANGE;

    public function __construct(UnitEnum $filter, private Money|int|float|null $min, private Money|int|float|null $max)
    {
        parent::__construct($filter);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'min' => 'int|float|null', 'max' => 'int|float|null'])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'min' => $this->getValue($this->min),
            'max' => $this->getValue($this->max),
        ]);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'min' => 'int|float|null', 'max' => 'int|float|null'])]
    public function allowed(): array
    {
        return $this->toArray();
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'min' => 'int|float|null', 'max' => 'int|float|null'])]
    public function applied(): array
    {
        return $this->toArray();
    }

    public function apply(Money|int|float|null $min, Money|int|float|null $max): self
    {
        if (isset($min, $max) && $min > $max) {
            [$max, $min] = [$min, $max];
        }

        $min = $min ?? $this->min;
        $max = $max ?? $this->max;

        $filter = clone $this;

        $filter->min = isset($min) ? MathUtils::clamp($min, $filter->min, $filter->max) : null;
        $filter->max = isset($max) ? MathUtils::clamp($max, $filter->min, $filter->max) : null;

        return $filter;
    }

    public function isset(): bool
    {
        return isset($this->min, $this->max);
    }

    private function getValue(Money|int|float|null $value): int|float|null
    {
        if ($value instanceof Money) {
            return $value->getValue();
        }

        return $value;
    }
}

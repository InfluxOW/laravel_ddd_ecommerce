<?php

namespace App\Components\Queryable\Classes\Filter;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Components\Generic\Utils\MathUtils;
use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;

final class RangeFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::RANGE;

    public Money|int|float|null $minValue;
    public Money|int|float|null $maxValue;
    public readonly ?Currency $currency;

    public function __construct(BackedEnum $filter, ?float $minValue, ?float $maxValue, ?string $currency)
    {
        parent::__construct($filter);

        $this->currency = ($currency === null) ? null : currency($currency);

        $this->minValue = isset($this->currency, $minValue) ? money($minValue, $this->currency->getCurrency()) : $minValue;
        $this->maxValue = isset($this->currency, $maxValue) ? money($maxValue, $this->currency->getCurrency()) : $maxValue;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'min_value' => ($this->minValue instanceof Money) ? $this->minValue->getValue() : $this->minValue,
            'max_value' => ($this->maxValue instanceof Money) ? $this->maxValue->getValue() : $this->maxValue,
        ]);
    }

    public function ofValues(mixed ...$values): self
    {
        [$selectedMinValue, $selectedMaxValue] = $values;
        if (isset($selectedMinValue, $selectedMaxValue) && $selectedMinValue > $selectedMaxValue) {
            [$selectedMaxValue, $selectedMinValue] = [$selectedMinValue, $selectedMaxValue];
        }

        $selectedMinValue = isset($this->currency, $selectedMinValue) ? money($selectedMinValue, $this->currency->getCurrency())->getValue() : $selectedMinValue;
        $selectedMaxValue = isset($this->currency, $selectedMaxValue) ? money($selectedMaxValue, $this->currency->getCurrency())->getValue() : $selectedMaxValue;

        $minValue = ($this->minValue instanceof Money) ? $this->minValue->getValue() : $this->minValue;
        $maxValue = ($this->maxValue instanceof Money) ? $this->maxValue->getValue() : $this->maxValue;

        $filter = clone($this);
        $filter->minValue = null;
        $filter->maxValue = null;

        if (isset($minValue, $maxValue)) {
            $filter->minValue = isset($selectedMinValue) ? MathUtils::clamp($selectedMinValue, $minValue, $maxValue) : $minValue;
            $filter->maxValue = isset($selectedMaxValue) ? MathUtils::clamp($selectedMaxValue, $minValue, $maxValue) : $maxValue;
        }

        return $filter;
    }
}

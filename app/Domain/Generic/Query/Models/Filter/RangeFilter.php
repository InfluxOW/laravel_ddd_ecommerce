<?php

namespace App\Domain\Generic\Query\Models\Filter;

use Akaunting\Money\Money;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Query\Enums\QueryFilterType;
use App\Domain\Generic\Utils\MathUtils;
use BackedEnum;
use JetBrains\PhpStorm\ArrayShape;

class RangeFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::RANGE;

    public Money|int|float|null $minValue;
    public Money|int|float|null $maxValue;
    public readonly ?string $currency;

    public function __construct(BackedEnum $filter, TranslationNamespace $namespace, ?float $minValue, ?float $maxValue, ?string $currency)
    {
        parent::__construct($filter, $namespace);

        $this->currency = $currency;

        $this->minValue = isset($this->currency) ? money($minValue, $currency) : $minValue;
        $this->maxValue = isset($this->currency) ? money($maxValue, $currency) : $maxValue;
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'min_value' => "float", 'max_value' => "float"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'min_value' => ($this->minValue instanceof Money) ? $this->minValue->getValue() : $this->minValue,
            'max_value' => ($this->maxValue instanceof Money) ? $this->maxValue->getValue() : $this->maxValue,
        ]);
    }

    public function ofValues(mixed ...$values): static
    {
        [$selectedMinValue, $selectedMaxValue] = $values;
        if (isset($selectedMinValue, $selectedMaxValue) && $selectedMinValue > $selectedMaxValue) {
            [$selectedMaxValue, $selectedMinValue] = [$selectedMinValue, $selectedMaxValue];
        }

        $filter = clone($this);
        $filter->minValue = null;
        $filter->maxValue = null;

        if (isset($this->minValue, $this->maxValue)) {
            $filter->minValue = isset($selectedMinValue) ? MathUtils::clamp($selectedMinValue, $this->minValue, $this->maxValue) : $this->minValue;
            $filter->maxValue = isset($selectedMaxValue) ? MathUtils::clamp($selectedMaxValue, $this->minValue, $this->maxValue) : $this->maxValue;
        }

        return $filter;
    }
}

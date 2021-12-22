<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Currency\Models\Kopecks;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Generic\Query\Enums\QueryFilterType;
use App\Domain\Generic\Utils\MathUtils;
use BackedEnum;
use JetBrains\PhpStorm\ArrayShape;

class RangeFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::RANGE;

    public float|int|null $minValue;
    public float|int|null $maxValue;
    public readonly bool $valuesAreCurrency;

    public function __construct(BackedEnum $filter, TranslationNamespace $namespace, ?float $minValue, ?float $maxValue, bool $valuesAreCurrency)
    {
        parent::__construct($filter, $namespace);

        $this->valuesAreCurrency = $valuesAreCurrency;

        $this->minValue = $this->valuesAreCurrency ? (int) (Kopecks::KOPECKS_IN_ROUBLE * $minValue) : $minValue;
        $this->maxValue = $this->valuesAreCurrency ? (int) (Kopecks::KOPECKS_IN_ROUBLE * $maxValue) : $maxValue;
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'min_value' => "float", 'max_value' => "float"])]
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'min_value' => $this->valuesAreCurrency ? $this->minValue / Kopecks::KOPECKS_IN_ROUBLE : $this->minValue,
            'max_value' => $this->valuesAreCurrency ? $this->maxValue / Kopecks::KOPECKS_IN_ROUBLE : $this->maxValue,
        ]);
    }

    public function ofValues(mixed ...$values): static
    {
        [$selectedMinValue, $selectedMaxValue] = $values;
        if ($selectedMinValue > $selectedMaxValue) {
            [$selectedMaxValue, $selectedMinValue] = [$selectedMinValue, $selectedMaxValue];
        }

        $filter = clone($this);
        $filter->minValue = null;
        $filter->maxValue = null;

        if (isset($this->minValue, $this->maxValue)) {
            if (isset($selectedMinValue)) {
                $filter->minValue = MathUtils::clamp($selectedMinValue, $this->minValue, $this->maxValue);
            }

            if (isset($selectedMaxValue)) {
                $filter->maxValue = MathUtils::clamp($selectedMaxValue, $this->minValue, $this->maxValue);
            }
        }

        return $filter;
    }
}

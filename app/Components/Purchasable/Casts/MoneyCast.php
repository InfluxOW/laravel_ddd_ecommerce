<?php

namespace App\Components\Purchasable\Casts;

use Akaunting\Money\Money;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param mixed $value
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     *
     * @return Money
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $currency = $attributes['currency'] ?? $model->currency ?? app(CatalogSettings::class)->default_currency;

        return $value === null ? null : money($value, $currency);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param mixed $value
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     *
     * @return int
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}

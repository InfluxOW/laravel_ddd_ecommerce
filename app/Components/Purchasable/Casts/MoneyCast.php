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
     * @param Model  $model
     * @param string $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return Money
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $currency = app(CatalogSettings::class)->default_currency;
        if (array_key_exists('currency', $attributes)) {
            $currency = $attributes['currency'];
        } elseif (isset($model->currency)) {
            $currency = $model->currency;
        }

        return ($value === null) ? null : money($value, $currency);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model  $model
     * @param string $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return int
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}

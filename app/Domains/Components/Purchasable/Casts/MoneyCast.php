<?php

namespace App\Domains\Components\Purchasable\Casts;

use Akaunting\Money\Money;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use function money;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Money
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $currency = app(CatalogSettings::class)->default_currency;
        if (array_key_exists('currency', $attributes)) {
            $currency = $attributes['currency'];
        } else if (isset($model->currency)) {
            $currency = $model->currency;
        }

        return is_int($value) ? money($value, $currency) : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return int
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return is_int($value) ? $value : null;
    }
}

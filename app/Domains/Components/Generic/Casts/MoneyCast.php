<?php

namespace App\Domains\Components\Generic\Casts;

use Akaunting\Money\Money;
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
        return is_int($value) ? money($value, $attributes['currency']) : null;
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

<?php

namespace App\Domain\Generic\Currency\Casts;

use App\Domain\Generic\Currency\Models\Kopecks;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class KopecksCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return Kopecks
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return is_int($value) ? new Kopecks($value) : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return int
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return is_int($value) ? $value : null;
    }
}

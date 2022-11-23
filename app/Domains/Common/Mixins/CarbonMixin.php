<?php

namespace App\Domains\Common\Mixins;

use Carbon\Carbon;
use Closure;

/**
 * @mixin Carbon
 *
 * @phpcs:disable PSR2.Methods.FunctionCallSignature.SpaceBeforeOpenBracket
 * */
final class CarbonMixin
{
    public function createFromDefaultFormat(): Closure
    {
        return fn (string $datetime): Carbon|false => self::createFromFormat(config('app.date_format'), $datetime);
    }

    public function defaultFormat(): Closure
    {
        return fn (): string => $this->format(config('app.date_format'));
    }
}

<?php

namespace App\Domain\Products\Models\Generic;

use App\Domain\Products\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Settings;

class ProductsSettings extends Settings
{
    public string $default_currency;
    public array $available_currencies;

    public static function group(): string
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE->value;
    }
}

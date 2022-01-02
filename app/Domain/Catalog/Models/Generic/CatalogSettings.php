<?php

namespace App\Domain\Catalog\Models\Generic;

use App\Domain\Catalog\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Settings;

class CatalogSettings extends Settings
{
    public string $default_currency;
    public array $available_currencies;

    public static function group(): string
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE->value;
    }
}

<?php

namespace App\Domains\Catalog\Models\Settings;

use App\Domains\Catalog\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Settings;

final class CatalogSettings extends Settings
{
    public string $default_currency;

    public array $available_currencies;

    public static function group(): string
    {
        return DomainServiceProvider::NAMESPACE->value;
    }
}

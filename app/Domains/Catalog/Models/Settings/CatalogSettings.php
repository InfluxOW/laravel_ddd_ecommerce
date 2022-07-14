<?php

namespace App\Domains\Catalog\Models\Settings;

use App\Domains\Catalog\Providers\DomainServiceProvider;
use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

final class CatalogSettings extends Settings
{
    public string $default_currency;

    public array $available_currencies;

    public array $required_currencies;

    public ?Carbon $products_displayability_last_updated_at;

    public ?Carbon $product_categories_displayability_last_updated_at;

    public static function group(): string
    {
        return DomainServiceProvider::NAMESPACE->value;
    }
}

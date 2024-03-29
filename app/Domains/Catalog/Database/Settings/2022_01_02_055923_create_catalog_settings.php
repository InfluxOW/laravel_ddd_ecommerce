<?php

namespace App\Domains\Catalog;

use Akaunting\Money\Currency;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class () extends SettingsMigration
{
    public function up(): void
    {
        $getPropertyName = static fn (string $property): string => sprintf('%s.%s', CatalogSettings::group(), $property);
        $usd = Currency::USD()->getCurrency();

        $this->migrator->add($getPropertyName('default_currency'), $usd);
        $this->migrator->add($getPropertyName('available_currencies'), [$usd]);
        $this->migrator->add($getPropertyName('required_currencies'), [$usd]);
        $this->migrator->add($getPropertyName('products_displayability_last_updated_at'));
        $this->migrator->add($getPropertyName('product_categories_displayability_last_updated_at'));
    }
};

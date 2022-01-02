<?php

namespace App\Domain\Catalog\Admin\Pages;

use Akaunting\Money\Currency;
use App\Domain\Admin\Traits\Translation\TranslatableAdminPage;
use App\Domain\Catalog\Enums\Translation\CatalogSettingsTranslationKey;
use App\Domain\Catalog\Models\Generic\ProductsSettings;
use App\Domain\Catalog\Providers\DomainServiceProvider;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Collection;

class ManageCatalogSettings extends SettingsPage
{
    use TranslatableAdminPage;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = ProductsSettings::class;

    protected static ?string $slug = 'settings/catalog';

    protected function getFormSchema(): array
    {
        return self::setTranslatableLabels([
            MultiSelect::make(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value)
                ->required()
                ->options(function (callable $get): array {
                    $currencies = collect(Currency::getCurrencies());
                    $availableCurrencies = $get(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value);

                    return $currencies
                        ->keys()
                        ->combine($currencies->pluck('name'))
                        ->sort()
                        ->filter(fn (string $key, string $value) => ! in_array($value, $availableCurrencies, true))
                        ->toArray();
                })
                ->getOptionLabelsUsing(fn (array $values): array => collect($values)->reduce(fn (Collection $acc, string $currency) => tap($acc, static fn () => $acc->offsetSet($currency, currency($currency)->getName())), collect([]))->toArray()),
            Select::make(CatalogSettingsTranslationKey::DEFAULT_CURRENCY->value)
                ->required()
                ->options(fn (callable $get): array => array_combine($get(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value), array_map(static fn (string $currency) => currency($currency)->getName(), $get(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value)))),
        ]);
    }

    /*
     * Translation
     * */

    protected static function getTranslationNamespace(): TranslationNamespace
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE;
    }

    protected static function getTranslationKeyClass(): string
    {
        return CatalogSettingsTranslationKey::class;
    }
}

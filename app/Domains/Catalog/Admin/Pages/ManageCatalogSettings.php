<?php

namespace App\Domains\Catalog\Admin\Pages;

use Akaunting\Money\Currency;
use App\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Admin\Admin\Abstracts\SettingsPage;
use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminPage;
use App\Domains\Catalog\Enums\Translation\CatalogSettingsTranslationKey;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;

class ManageCatalogSettings extends SettingsPage
{
    use TranslatableAdminPage;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;

    protected static string $settings = CatalogSettings::class;

    protected static ?string $slug = 'settings/catalog';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

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
                ->getOptionLabelsUsing(fn (array $values): array => collect($values)->reduce(fn (Collection $acc, string $currency): Collection => tap($acc, static fn () => $acc->offsetSet($currency, currency($currency)->getName())), collect([]))->toArray()),
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

<?php

namespace App\Domains\Catalog\Admin\Pages;

use Akaunting\Money\Currency;
use App\Domains\Admin\Admin\Abstracts\SettingsPage;
use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\TranslatableAdminPage;
use App\Domains\Catalog\Enums\Translation\CatalogSettingsTranslationKey;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;

final class ManageCatalogSettings extends SettingsPage
{
    use TranslatableAdminPage;
    use HasNavigationSort;

    protected static string $settings = CatalogSettings::class;

    protected static ?string $slug = 'settings/catalog';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected function getFormSchema(): array
    {
        return [
            MultiSelect::makeTranslated(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES)
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
            MultiSelect::makeTranslated(CatalogSettingsTranslationKey::REQUIRED_CURRENCIES)
                ->required()
                ->options(function (callable $get): array {
                    $currencies = collect(Currency::getCurrencies());
                    $availableCurrencies = $get(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value);
                    $requiredCurrencies = $get(CatalogSettingsTranslationKey::REQUIRED_CURRENCIES->value);

                    return $currencies
                        ->keys()
                        ->combine($currencies->pluck('name'))
                        ->sort()
                        ->filter(fn (string $key, string $value) => in_array($value, $availableCurrencies, true) && ! in_array($value, $requiredCurrencies, true))
                        ->toArray();
                })
                ->getOptionLabelsUsing(fn (array $values): array => collect($values)->reduce(fn (Collection $acc, string $currency): Collection => tap($acc, static fn () => $acc->offsetSet($currency, currency($currency)->getName())), collect([]))->toArray()),

            Select::makeTranslated(CatalogSettingsTranslationKey::DEFAULT_CURRENCY)
                ->required()
                ->options(fn (callable $get): array => array_combine($get(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value), array_map(static fn (string $currency) => currency($currency)->getName(), $get(CatalogSettingsTranslationKey::AVAILABLE_CURRENCIES->value)))),
        ];
    }
}

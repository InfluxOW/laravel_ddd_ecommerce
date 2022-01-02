<?php

namespace App\Domain\Products\Admin\Pages;

use Akaunting\Money\Currency;
use App\Domain\Admin\Traits\Translation\TranslatableAdminPage;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Products\Enums\Translation\ProductSettingsTranslationKey;
use App\Domain\Products\Models\Generic\ProductsSettings;
use App\Domain\Products\Providers\DomainServiceProvider;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Collection;

class ManageProductsSettings extends SettingsPage
{
    use TranslatableAdminPage;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = ProductsSettings::class;

    protected function getFormSchema(): array
    {
        return self::setTranslatableLabels([
            MultiSelect::make(ProductSettingsTranslationKey::AVAILABLE_CURRENCIES->value)
                ->required()
                ->options(function (callable $get): array {
                    $currencies = collect(Currency::getCurrencies());
                    $availableCurrencies = $get(ProductSettingsTranslationKey::AVAILABLE_CURRENCIES->value);

                    return $currencies
                        ->keys()
                        ->combine($currencies->pluck('name'))
                        ->sort()
                        ->filter(fn (string $key, string $value) => ! in_array($value, $availableCurrencies, true))
                        ->toArray();
                })
                ->getOptionLabelsUsing(fn (array $values): array => collect($values)->reduce(fn (Collection $acc, string $currency) => tap($acc, static fn () => $acc->offsetSet($currency, currency($currency)->getName())), collect([]))->toArray()),
            Select::make(ProductSettingsTranslationKey::DEFAULT_CURRENCY->value)
                ->required()
                ->options(fn (callable $get): array => array_combine($get(ProductSettingsTranslationKey::AVAILABLE_CURRENCIES->value), array_map(static fn (string $currency) => currency($currency)->getName(), $get(ProductSettingsTranslationKey::AVAILABLE_CURRENCIES->value)))),
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
        return ProductSettingsTranslationKey::class;
    }
}

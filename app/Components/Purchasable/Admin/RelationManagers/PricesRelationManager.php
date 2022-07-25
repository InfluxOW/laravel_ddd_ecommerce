<?php

namespace App\Components\Purchasable\Admin\RelationManagers;

use App\Components\Purchasable\Enums\Translation\PriceTranslationKey;
use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

final class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    public static function form(Form $form): Form
    {
        $availableCurrencies = collect(app(CatalogSettings::class)->available_currencies);

        return $form
            ->schema(self::setTranslatableLabels([
                Select::make(PriceTranslationKey::CURRENCY->value)
                    ->required()
                    ->options(function (RelationManager $livewire) use ($availableCurrencies): array {
                        $currencies = $availableCurrencies->filter(fn (string $currency): bool => isset($livewire->ownerRecord->prices) && $livewire->ownerRecord->prices->pluck('currency')->doesntContain($currency));

                        return $currencies->combine($currencies)->toArray();
                    })
                    ->searchable()
                    ->columnSpan(2),
                TextInput::make(PriceTranslationKey::PRICE->value)
                    ->required()
                    ->integer()
                    ->disabled(fn (callable $get): bool => $get(PriceTranslationKey::CURRENCY->value) === null)
                    ->afterStateHydrated(function (TextInput $component, ?array $state): void {
                        $amount = $state['amount'] ?? null;
                        if (isset($amount)) {
                            $component->state($amount);
                        }
                    })
                    ->dehydrateStateUsing(fn (string $state): int => (int) $state),
                TextInput::make(PriceTranslationKey::PRICE_DISCOUNTED->value)
                    ->nullable()
                    ->disabled(fn (callable $get): bool => $get(PriceTranslationKey::CURRENCY->value) === null)
                    ->integer()
                    ->afterStateHydrated(function (TextInput $component, ?array $state): void {
                        $component->state(($state === null) ? null : $state['amount']);
                    })
                    ->dehydrateStateUsing(fn (?string $state): ?int => ($state === null) ? null : (int) $state),
            ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(PriceTranslationKey::CURRENCY->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(PriceTranslationKey::PRICE->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(PriceTranslationKey::PRICE_DISCOUNTED->value)
                    ->sortable()
                    ->searchable(),
            ]));
    }

    /*
     * Policies
     * */

    protected function canCreate(): bool
    {
        return parent::canCreate() && isset($this->ownerRecord->prices) && $this->ownerRecord->prices->count() < count(app(CatalogSettings::class)->available_currencies);
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return PriceTranslationKey::class;
    }
}

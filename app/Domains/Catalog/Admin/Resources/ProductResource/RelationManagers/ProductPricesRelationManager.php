<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Catalog\Enums\Translation\ProductPriceTranslationKey;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

final class ProductPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    public static function form(Form $form): Form
    {
        $availableCurrencies = collect(app(CatalogSettings::class)->available_currencies);

        return $form
            ->schema(self::setTranslatableLabels([
                Select::make(ProductPriceTranslationKey::CURRENCY->value)
                    ->required()
                    ->options(function (RelationManager $livewire) use ($availableCurrencies): array {
                        $currencies = $availableCurrencies
                            ->filter(function (string $currency) use ($livewire): bool {
                                /** @var Product $product */
                                $product = $livewire->ownerRecord;

                                return $product->prices->pluck('currency')->doesntContain($currency);
                            });

                        return $currencies->combine($currencies)->toArray();
                    })
                    ->searchable()
                    ->columnSpan(2),
                TextInput::make(ProductPriceTranslationKey::PRICE->value)
                    ->required()
                    ->integer()
                    ->disabled(fn (callable $get): bool => $get(ProductPriceTranslationKey::CURRENCY->value) === null)
                    ->afterStateHydrated(function (TextInput $component, ?array $state): void {
                        $amount = $state['amount'] ?? null;
                        if (isset($amount)) {
                            $component->state($amount);
                        }
                    })
                    ->dehydrateStateUsing(fn (string $state): int => (int) $state),
                TextInput::make(ProductPriceTranslationKey::PRICE_DISCOUNTED->value)
                    ->nullable()
                    ->disabled(fn (callable $get): bool => $get(ProductPriceTranslationKey::CURRENCY->value) === null)
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
                TextColumn::make(ProductPriceTranslationKey::CURRENCY->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(ProductPriceTranslationKey::PRICE->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(ProductPriceTranslationKey::PRICE_DISCOUNTED->value)
                    ->sortable()
                    ->searchable(),
            ]));
    }

    /*
     * Policies
     * */

    protected function canCreate(): bool
    {
        /** @var Product $product */
        $product = $this->ownerRecord;

        return $product->prices->count() < count(app(CatalogSettings::class)->available_currencies) && $this->shouldBeDisplayed();
    }

    protected function canDeleteAny(): bool
    {
        return $this->shouldBeDisplayed();
    }

    protected function canDelete(Model $record): bool
    {
        return $this->shouldBeDisplayed();
    }

    protected function canEdit(Model $record): bool
    {
        return $this->shouldBeDisplayed();
    }

    protected function getViewableResourcesMap(): array
    {
        return [ProductResource::class => ProductResource\Pages\ViewProduct::class];
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return ProductPriceTranslationKey::class;
    }
}

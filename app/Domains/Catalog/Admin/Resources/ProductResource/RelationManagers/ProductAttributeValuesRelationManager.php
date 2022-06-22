<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Enums\Translation\ProductAttributeValueResourceTranslationKey;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductAttribute;
use App\Domains\Catalog\Models\ProductAttributeValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

final class ProductAttributeValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributeValues';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::setTranslatableLabels([
                Select::make(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value)
                    ->required()
                    ->id(function (RelationManager $livewire): string {
                        /** @var Product $product */
                        $product = $livewire->ownerRecord;

                        return $product->attributeValues->pluck('attribute_id')->implode('|');
                    })
                    ->relationship('attribute', 'title')
                    ->options(function (?ProductAttributeValue $record, RelationManager $livewire): array {
                        if ($livewire->canCreate()) {
                            /** @var Product $product */
                            $product = $livewire->ownerRecord;

                            return ProductAttribute::query()
                                ->whereIntegerNotInRaw('id', $product->attributeValues->pluck('attribute_id'))
                                ->orderBy('title')
                                ->pluck('title', 'id')
                                ->toArray();
                        }

                        if ($record === null) {
                            return [];
                        }

                        return [$record->attribute->id => $record->attribute->title];
                    })
                    ->reactive()
                    ->searchable(fn (RelationManager $livewire): bool => $livewire->canCreate()),
                TextInput::make(ProductAttributeValueResourceTranslationKey::VALUE_STRING->value)
                    ->required()
                    ->hidden(fn (callable $get): bool => $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => ProductAttribute::query()->where('id', $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value))->first()?->values_type === ProductAttributeValuesType::STRING),
                TextInput::make(ProductAttributeValueResourceTranslationKey::VALUE_INTEGER->value)
                    ->required()
                    ->integer()
                    ->hidden(fn (callable $get): bool => $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => ProductAttribute::query()->where('id', $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value))->first()?->values_type === ProductAttributeValuesType::INTEGER),
                TextInput::make(ProductAttributeValueResourceTranslationKey::VALUE_FLOAT->value)
                    ->required()
                    ->numeric()
                    ->hidden(fn (callable $get): bool => $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => ProductAttribute::query()->where('id', $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value))->first()?->values_type === ProductAttributeValuesType::FLOAT),
                Toggle::make(ProductAttributeValueResourceTranslationKey::VALUE_BOOLEAN->value)
                    ->required()
                    ->inline(false)
                    ->hidden(fn (callable $get): bool => $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => ProductAttribute::query()->where('id', $get(ProductAttributeValueResourceTranslationKey::ATTRIBUTE->value))->first()?->values_type === ProductAttributeValuesType::BOOLEAN),
            ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(ProductAttributeValueResourceTranslationKey::ATTRIBUTE_TITLE->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(ProductAttributeValueResourceTranslationKey::READABLE_VALUE->value),
            ]))
            ->filters([
                //
            ]);
    }

    /*
     * Policies
     * */

    protected function canCreate(): bool
    {
        /** @var Product $product */
        $product = $this->ownerRecord;

        return $product->attributeValues->count() < ProductAttribute::query()->count() && $this->shouldBeDisplayed();
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
        return ProductAttributeValueResourceTranslationKey::class;
    }
}

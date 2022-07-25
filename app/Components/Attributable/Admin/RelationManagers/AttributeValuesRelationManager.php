<?php

namespace App\Components\Attributable\Admin\RelationManagers;

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Enums\Translation\AttributeValueTranslationKey;
use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

final class AttributeValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributeValues';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::setTranslatableLabels([
                Select::make(AttributeValueTranslationKey::ATTRIBUTE->value)
                    ->required()
                    ->id(fn (RelationManager $livewire): ?string => isset($livewire->ownerRecord->attributeValues) ? $livewire->ownerRecord->attributeValues->pluck('attribute_id')->implode('|') : null)
                    ->relationship('attribute', 'title')
                    ->options(function (?AttributeValue $record, RelationManager $livewire): array {
                        if (isset($livewire->ownerRecord->attributeValues) && $livewire->canCreate()) {
                            return Attribute::query()
                                ->whereIntegerNotInRaw('id', $livewire->ownerRecord->attributeValues->pluck('attribute_id'))
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
                TextInput::make(AttributeValueTranslationKey::VALUE_STRING->value)
                    ->required()
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::STRING),
                TextInput::make(AttributeValueTranslationKey::VALUE_INTEGER->value)
                    ->required()
                    ->integer()
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::INTEGER),
                TextInput::make(AttributeValueTranslationKey::VALUE_FLOAT->value)
                    ->required()
                    ->numeric()
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::FLOAT),
                Toggle::make(AttributeValueTranslationKey::VALUE_BOOLEAN->value)
                    ->required()
                    ->inline(false)
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::BOOLEAN),
            ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(AttributeValueTranslationKey::ATTRIBUTE_TITLE->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(AttributeValueTranslationKey::READABLE_VALUE->value),
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
        return isset($this->ownerRecord->attributeValues) && $this->ownerRecord->attributeValues->count() < Attribute::query()->count() && $this->shouldBeDisplayed();
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
        return AttributeValueTranslationKey::class;
    }
}

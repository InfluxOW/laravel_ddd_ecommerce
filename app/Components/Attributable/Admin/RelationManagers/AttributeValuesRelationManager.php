<?php

namespace App\Components\Attributable\Admin\RelationManagers;

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Enums\Translation\AttributeValueTranslationKey;
use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Domains\Admin\Admin\Abstracts\RelationManager;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

final class AttributeValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributeValues';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::makeTranslated(AttributeValueTranslationKey::ATTRIBUTE)
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
                TextInput::makeTranslated(AttributeValueTranslationKey::VALUE_STRING)
                    ->required()
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::STRING),
                TextInput::makeTranslated(AttributeValueTranslationKey::VALUE_INTEGER)
                    ->required()
                    ->integer()
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::INTEGER),
                TextInput::makeTranslated(AttributeValueTranslationKey::VALUE_FLOAT)
                    ->required()
                    ->numeric()
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::FLOAT),
                Toggle::makeTranslated(AttributeValueTranslationKey::VALUE_BOOLEAN)
                    ->required()
                    ->inline(false)
                    ->hidden(fn (callable $get): bool => $get(AttributeValueTranslationKey::ATTRIBUTE->value) === null)
                    ->visible(fn (callable $get): bool => Attribute::query()->where('id', $get(AttributeValueTranslationKey::ATTRIBUTE->value))->first()?->values_type === AttributeValuesType::BOOLEAN),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(AttributeValueTranslationKey::ATTRIBUTE_TITLE)
                    ->sortable()
                    ->searchable(),
                TextColumn::makeTranslated(AttributeValueTranslationKey::READABLE_VALUE),
            ])
            ->filters([
                //
            ]);
    }

    /*
     * Policies
     * */

    public function canCreate(): bool
    {
        return parent::canCreate() && isset($this->ownerRecord->attributeValues) && $this->ownerRecord->attributeValues->count() < Attribute::query()->count();
    }
}

<?php

namespace App\Components\Addressable\Admin\RelationManagers;

use App\Components\Addressable\Enums\Translation\AddressesTranslationKey;
use App\Components\Addressable\Models\Address;
use App\Domains\Admin\Admin\Abstracts\RelationManager;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Squire\Models\Country;
use Squire\Models\Region;

final class AddressesRelationManager extends RelationManager
{
    protected static ?string $recordTitleAttribute = 'string_representation';

    protected static string $relationship = 'addresses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::makeTranslated(AddressesTranslationKey::COUNTRY)
                    ->searchable()
                    ->options(Country::query()->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $query): array => Country::query()->where('name', 'LIKE', "%{$query}%")->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn (?string $value): ?string => ($value === null) ? null : Country::query()->find($value)?->name)
                    ->afterStateUpdated(function (callable $set): void {
                        $set(AddressesTranslationKey::REGION->value, null);
                        $set(AddressesTranslationKey::CITY->value, null);
                        $set(AddressesTranslationKey::STREET->value, null);
                        $set(AddressesTranslationKey::ZIP->value, null);
                    })
                    ->reactive()
                    ->columnSpan(1),
                Select::makeTranslated(AddressesTranslationKey::REGION)
                    ->id(fn (callable $get, self $livewire) => sprintf('%s|%s|%s', $livewire->ownerRecord->getKey(), $get(AddressesTranslationKey::COUNTRY->value), $get(AddressesTranslationKey::REGION->value)))
                    ->disabled(fn (callable $get): bool => $get(AddressesTranslationKey::COUNTRY->value) === null)
                    ->searchable()
                    ->options(fn (callable $get): array => Region::query()->where('country_id', $get(AddressesTranslationKey::COUNTRY->value))->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $query, callable $get): array => Region::query()->where('country_id', $get(AddressesTranslationKey::COUNTRY->value))->where('name', 'LIKE', "%{$query}%")->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn (?string $value): ?string => ($value === null) ? null : Region::query()->find($value)?->name)
                    ->afterStateUpdated(function (callable $set): void {
                        $set(AddressesTranslationKey::CITY->value, null);
                        $set(AddressesTranslationKey::STREET->value, null);
                        $set(AddressesTranslationKey::ZIP->value, null);
                    })
                    ->reactive()
                    ->columnSpan(1),
                TextInput::makeTranslated(AddressesTranslationKey::CITY)->columnSpan(1),
                TextInput::makeTranslated(AddressesTranslationKey::STREET)->columnSpan(1),
                TextInput::makeTranslated(AddressesTranslationKey::ZIP)->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(AddressesTranslationKey::COUNTRY)->sortable()->searchable()->formatStateUsing(fn (Address $record) => $record->getCountry()?->name),
                TextColumn::makeTranslated(AddressesTranslationKey::REGION)->sortable()->searchable()->formatStateUsing(fn (Address $record) => $record->getRegion()?->name),
                TextColumn::makeTranslated(AddressesTranslationKey::CITY)->sortable()->searchable(),
                TextColumn::makeTranslated(AddressesTranslationKey::STREET)->sortable()->searchable(),
                TextColumn::makeTranslated(AddressesTranslationKey::ZIP)->sortable()->searchable(),
            ])
            ->filters([
                //
            ]);
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AddressesTranslationKey::class;
    }
}

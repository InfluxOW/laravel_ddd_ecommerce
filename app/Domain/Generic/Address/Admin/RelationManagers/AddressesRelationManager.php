<?php

namespace App\Domain\Generic\Address\Admin\RelationManagers;

use App\Domain\Generic\Address\Models\Address;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\MorphManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Squire\Models\Country;
use Squire\Models\Region;

class AddressesRelationManager extends MorphManyRelationManager
{
    protected static ?string $label = 'Address';

    protected static ?string $pluralLabel = 'Addresses';

    protected static string $relationship = 'addresses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('zip')->label('Zip / Postal Code')->columnSpan(2),
                Select::make('country')
                    ->searchable()
                    ->options(Country::query()->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $query): array => Country::query()->where('name', 'LIKE', "%{$query}%")->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn (?string $value): ?string => ($value === null) ? null : Country::query()->find($value)?->name)
                    ->afterStateUpdated(fn (callable $set) => $set('region', null))
                    ->reactive(),
                Select::make('region')
                    ->id(fn (callable $get, self $livewire) => sprintf('%s|%s|%s', $livewire->ownerRecord->id, $get('country'), $get('region')))
                    ->disabled(fn (callable $get): bool => $get('country') === null)
                    ->searchable()
                    ->options(fn (callable $get): array => Region::query()->where('country_id', $get('country'))->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $query, callable $get): array => Region::query()->where('country_id', $get('country'))->where('name', 'LIKE', "%{$query}%")->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn (?string $value): ?string => ($value === null) ? null : Region::query()->find($value)?->name),
                TextInput::make('city'),
                TextInput::make('street')->label('Street Address'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('zip')->label('Zip / Postal Code')->sortable()->searchable(),
                TextColumn::make('country')->sortable()->searchable()->formatStateUsing(fn (Address $record) => $record->getCountry()?->name),
                TextColumn::make('region')->sortable()->searchable()->formatStateUsing(fn (Address $record) => $record->getRegion()?->name),
                TextColumn::make('city')->sortable()->searchable(),
                TextColumn::make('street')->label('Street Address')->sortable()->searchable(),
            ])
            ->filters([
                //
            ]);
    }
}

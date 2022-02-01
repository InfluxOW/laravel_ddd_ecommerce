<?php

namespace App\Components\Addressable\Admin\RelationManagers;

use App\Components\Addressable\Enums\Translation\AddressesRelationManagerTranslationKey;
use App\Components\Addressable\Models\Address;
use App\Domains\Admin\Admin\Abstracts\RelationManagers\MorphManyRelationManager;
use App\Domains\Users\Admin\Resources\UserResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Squire\Models\Country;
use Squire\Models\Region;

final class AddressesRelationManager extends MorphManyRelationManager
{
    protected static ?string $recordTitleAttribute = 'string_representation';
    protected static string $relationship = 'addresses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::setTranslatableLabels([
                TextInput::make(AddressesRelationManagerTranslationKey::ZIP->value)->columnSpan(2),
                Select::make(AddressesRelationManagerTranslationKey::COUNTRY->value)
                    ->searchable()
                    ->options(Country::query()->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $query): array => Country::query()->where('name', 'LIKE', "%{$query}%")->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn (?string $value): ?string => ($value === null) ? null : Country::query()->find($value)?->name)
                    ->afterStateUpdated(fn (callable $set) => $set(AddressesRelationManagerTranslationKey::REGION->value, null))
                    ->reactive(),
                Select::make(AddressesRelationManagerTranslationKey::REGION->value)
                    ->id(fn (callable $get, self $livewire) => sprintf('%s|%s|%s', $livewire->ownerRecord->getKey(), $get(AddressesRelationManagerTranslationKey::COUNTRY->value), $get(AddressesRelationManagerTranslationKey::REGION->value)))
                    ->disabled(fn (callable $get): bool => $get(AddressesRelationManagerTranslationKey::COUNTRY->value) === null)
                    ->searchable()
                    ->options(fn (callable $get): array => Region::query()->where('country_id', $get(AddressesRelationManagerTranslationKey::COUNTRY->value))->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $query, callable $get): array => Region::query()->where('country_id', $get(AddressesRelationManagerTranslationKey::COUNTRY->value))->where('name', 'LIKE', "%{$query}%")->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn (?string $value): ?string => ($value === null) ? null : Region::query()->find($value)?->name),
                TextInput::make(AddressesRelationManagerTranslationKey::CITY->value),
                TextInput::make(AddressesRelationManagerTranslationKey::STREET->value),
            ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(AddressesRelationManagerTranslationKey::ZIP->value)->sortable()->searchable(),
                TextColumn::make(AddressesRelationManagerTranslationKey::REGION->value)->sortable()->searchable()->formatStateUsing(fn (Address $record) => $record->getCountry()?->name),
                TextColumn::make(AddressesRelationManagerTranslationKey::COUNTRY->value)->sortable()->searchable()->formatStateUsing(fn (Address $record) => $record->getRegion()?->name),
                TextColumn::make(AddressesRelationManagerTranslationKey::CITY->value)->sortable()->searchable(),
                TextColumn::make(AddressesRelationManagerTranslationKey::STREET->value)->sortable()->searchable(),
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
        return $this->shouldBeDisplayed();
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

    private function shouldBeDisplayed(): bool
    {
        return collect([
            UserResource::getUrl('view', $this->ownerRecord->getKey()),
            route('livewire.message', ['generic.admin.resources.user-resource.pages.view-user']),
        ])->doesntContain(Request::url());
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AddressesRelationManagerTranslationKey::class;
    }
}

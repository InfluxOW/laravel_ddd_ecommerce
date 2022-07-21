<?php

namespace App\Domains\Users\Admin\Resources\UserResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Users\Admin\Resources\UserResource;
use App\Domains\Users\Enums\Translation\LoginHistoryTranslationKey;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Geometries\Point;

class UserLoginHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'loginHistory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::setTranslatableLabels([
                TextInput::make(LoginHistoryTranslationKey::IP->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::IP->value}))->columnSpan(3),
                TextInput::make(LoginHistoryTranslationKey::USER_AGENT->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::USER_AGENT->value}))->columnSpan(3),
                TextInput::make(LoginHistoryTranslationKey::DEVICE->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::DEVICE->value}))->columnSpan(1),
                TextInput::make(LoginHistoryTranslationKey::PLATFORM->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::PLATFORM->value}))->columnSpan(1),
                TextInput::make(LoginHistoryTranslationKey::PLATFORM_VERSION->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::PLATFORM_VERSION->value}))->columnSpan(1),
                TextInput::make(LoginHistoryTranslationKey::BROWSER->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::BROWSER->value}))->columnSpan(2),
                TextInput::make(LoginHistoryTranslationKey::BROWSER_VERSION->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::BROWSER_VERSION->value}))->columnSpan(1),
                TextInput::make(LoginHistoryTranslationKey::COUNTRY_CODE->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::COUNTRY_CODE->value}))->columnSpan(1),
                TextInput::make(LoginHistoryTranslationKey::COUNTRY_NAME->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::COUNTRY_NAME->value}))->columnSpan(2),
                TextInput::make(LoginHistoryTranslationKey::REGION_CODE->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::REGION_CODE->value}))->columnSpan(1),
                TextInput::make(LoginHistoryTranslationKey::REGION_NAME->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::REGION_NAME->value}))->columnSpan(2),
                TextInput::make(LoginHistoryTranslationKey::CITY->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::CITY->value}))->columnSpan(2),
                TextInput::make(LoginHistoryTranslationKey::ZIP->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::ZIP->value}))->columnSpan(1),
                OSMMap::make(LoginHistoryTranslationKey::LOCATION->value)
                    ->showMarker()
                    ->draggable()
                    ->zoom(10)
                    ->showZoomControl()
                    ->afterStateHydrated(function (array|Point|null $state, callable $set) {
                        if ($state instanceof Point) {
                            /** @var Point $state */
                            $set(LoginHistoryTranslationKey::LOCATION->value, ['lat' => $state->getLat(), 'lng' => $state->getLng()]);
                        }
                    })
                    ->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::LOCATION->value}))
                    ->columnSpan(3),
                DateTimePicker::make(LoginHistoryTranslationKey::TIME->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::TIME->value})),
            ]))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(LoginHistoryTranslationKey::IP->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(LoginHistoryTranslationKey::USER_AGENT->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(LoginHistoryTranslationKey::TIME->value)
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ]))
            ->filters([
                //
            ]);
    }

    /*
     * Policies
     * */

    protected function canView(Model $record): bool
    {
        return true;
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canDeleteAny(): bool
    {
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        return false;
    }

    protected function canEdit(Model $record): bool
    {
        return false;
    }

    protected function getViewableResourcesMap(): array
    {
        return [UserResource::class => UserResource\Pages\ViewUser::class];
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return LoginHistoryTranslationKey::class;
    }
}

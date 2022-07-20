<?php

namespace App\Domains\Users\Admin\Resources\UserResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Users\Admin\Resources\UserResource;
use App\Domains\Users\Enums\Translation\LoginHistoryResourceTranslationKey;
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
                TextInput::make(LoginHistoryResourceTranslationKey::IP->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::IP->value}))->columnSpan(3),
                TextInput::make(LoginHistoryResourceTranslationKey::USER_AGENT->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::USER_AGENT->value}))->columnSpan(3),
                TextInput::make(LoginHistoryResourceTranslationKey::DEVICE->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::DEVICE->value}))->columnSpan(1),
                TextInput::make(LoginHistoryResourceTranslationKey::PLATFORM->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::PLATFORM->value}))->columnSpan(1),
                TextInput::make(LoginHistoryResourceTranslationKey::PLATFORM_VERSION->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::PLATFORM_VERSION->value}))->columnSpan(1),
                TextInput::make(LoginHistoryResourceTranslationKey::BROWSER->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::BROWSER->value}))->columnSpan(2),
                TextInput::make(LoginHistoryResourceTranslationKey::BROWSER_VERSION->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::BROWSER_VERSION->value}))->columnSpan(1),
                TextInput::make(LoginHistoryResourceTranslationKey::COUNTRY_CODE->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::COUNTRY_CODE->value}))->columnSpan(1),
                TextInput::make(LoginHistoryResourceTranslationKey::COUNTRY_NAME->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::COUNTRY_NAME->value}))->columnSpan(2),
                TextInput::make(LoginHistoryResourceTranslationKey::REGION_CODE->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::REGION_CODE->value}))->columnSpan(1),
                TextInput::make(LoginHistoryResourceTranslationKey::REGION_NAME->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::REGION_NAME->value}))->columnSpan(2),
                TextInput::make(LoginHistoryResourceTranslationKey::CITY->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::CITY->value}))->columnSpan(2),
                TextInput::make(LoginHistoryResourceTranslationKey::ZIP->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::ZIP->value}))->columnSpan(1),
                OSMMap::make(LoginHistoryResourceTranslationKey::LOCATION->value)
                    ->showMarker()
                    ->draggable()
                    ->zoom(10)
                    ->showZoomControl()
                    ->afterStateHydrated(function (array|Point|null $state, callable $set) {
                        if ($state instanceof Point) {
                            /** @var Point $state */
                            $set(LoginHistoryResourceTranslationKey::LOCATION->value, ['lat' => $state->getLat(), 'lng' => $state->getLng()]);
                        }
                    })
                    ->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::LOCATION->value}))
                    ->columnSpan(3),
                DateTimePicker::make(LoginHistoryResourceTranslationKey::TIME->value)->visible(fn (Model $record): bool => isset($record->{LoginHistoryResourceTranslationKey::TIME->value})),
            ]))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(LoginHistoryResourceTranslationKey::IP->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(LoginHistoryResourceTranslationKey::USER_AGENT->value)
                    ->sortable()
                    ->searchable(),
                TextColumn::make(LoginHistoryResourceTranslationKey::TIME->value)
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
        return LoginHistoryResourceTranslationKey::class;
    }
}

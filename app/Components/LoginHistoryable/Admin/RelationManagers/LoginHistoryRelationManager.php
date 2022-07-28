<?php

namespace App\Components\LoginHistoryable\Admin\RelationManagers;

use App\Components\LoginHistoryable\Enums\Translation\LoginHistoryTranslationKey;
use App\Domains\Admin\Admin\Abstracts\RelationManager;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Geometries\Point;

class LoginHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'loginHistory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::makeTranslated(LoginHistoryTranslationKey::IP)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::IP->value}))->columnSpan(3),
                TextInput::makeTranslated(LoginHistoryTranslationKey::USER_AGENT)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::USER_AGENT->value}))->columnSpan(3),
                TextInput::makeTranslated(LoginHistoryTranslationKey::DEVICE)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::DEVICE->value}))->columnSpan(1),
                TextInput::makeTranslated(LoginHistoryTranslationKey::PLATFORM)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::PLATFORM->value}))->columnSpan(1),
                TextInput::makeTranslated(LoginHistoryTranslationKey::PLATFORM_VERSION)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::PLATFORM_VERSION->value}))->columnSpan(1),
                TextInput::makeTranslated(LoginHistoryTranslationKey::BROWSER)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::BROWSER->value}))->columnSpan(2),
                TextInput::makeTranslated(LoginHistoryTranslationKey::BROWSER_VERSION)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::BROWSER_VERSION->value}))->columnSpan(1),
                TextInput::makeTranslated(LoginHistoryTranslationKey::COUNTRY_CODE)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::COUNTRY_CODE->value}))->columnSpan(1),
                TextInput::makeTranslated(LoginHistoryTranslationKey::COUNTRY_NAME)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::COUNTRY_NAME->value}))->columnSpan(2),
                TextInput::makeTranslated(LoginHistoryTranslationKey::REGION_CODE)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::REGION_CODE->value}))->columnSpan(1),
                TextInput::makeTranslated(LoginHistoryTranslationKey::REGION_NAME)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::REGION_NAME->value}))->columnSpan(2),
                TextInput::makeTranslated(LoginHistoryTranslationKey::CITY)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::CITY->value}))->columnSpan(2),
                TextInput::makeTranslated(LoginHistoryTranslationKey::ZIP)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::ZIP->value}))->columnSpan(1),
                OSMMap::makeTranslated(LoginHistoryTranslationKey::LOCATION)
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
                DateTimePicker::makeTranslated(LoginHistoryTranslationKey::TIME)->visible(fn (Model $record): bool => isset($record->{LoginHistoryTranslationKey::TIME->value})),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(LoginHistoryTranslationKey::IP)
                    ->sortable()
                    ->searchable(),
                TextColumn::makeTranslated(LoginHistoryTranslationKey::USER_AGENT)
                    ->sortable()
                    ->searchable(),
                TextColumn::makeTranslated(LoginHistoryTranslationKey::TIME)
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
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
        return false;
    }

    public function canEdit(Model $record): bool
    {
        return false;
    }

    public function canDelete(Model $record): bool
    {
        return false;
    }

    protected function canDeleteAny(): bool
    {
        return false;
    }
}

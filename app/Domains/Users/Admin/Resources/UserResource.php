<?php

namespace App\Domains\Users\Admin\Resources;

use App\Components\Addressable\Admin\RelationManagers\AddressesRelationManager;
use App\Components\LoginHistoryable\Admin\RelationManagers\LoginHistoryRelationManager;
use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Users\Admin\Resources\UserResource\Pages\EditUser;
use App\Domains\Users\Admin\Resources\UserResource\Pages\ListUsers;
use App\Domains\Users\Admin\Resources\UserResource\Pages\ViewUser;
use App\Domains\Users\Enums\Translation\UserTranslationKey;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Ysfkaya\FilamentPhoneInput\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    /** @param  User  $record */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $result = [
            'E-mail' => $record->email,
        ];

        if (isset($record->phone)) {
            $result['Phone'] = $record->phone;
        }

        return $result;
    }

    /*
     * Data
     * */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(UserTranslationKey::NAME)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('John Doe')
                                    ->columnSpan(1),
                                TextInput::makeTranslated(UserTranslationKey::EMAIL)
                                    ->required()
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('john_doe@gmail.com')
                                    ->columnSpan(1),
                                PhoneInput::makeTranslated(UserTranslationKey::PHONE)
                                    ->focusNumberFormat(PhoneInputNumberType::E164)
                                    ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                                    ->columnSpan(1),
                            ])
                            ->columnSpan(3)
                            ->columns(3),
                        TextInput::makeTranslated(UserTranslationKey::PASSWORD)
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->password()
                            ->hidden(fn (Page $livewire): bool => $livewire instanceof ViewRecord)
                            ->dehydrated(fn (?string $state): bool => isset($state))
                            ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                            ->minLength(6)
                            ->maxLength(255)
                            ->placeholder('Password')
                            ->columnSpan(3),
                        DateTimePicker::makeTranslated(UserTranslationKey::EMAIL_VERIFIED_AT)
                            ->nullable()
                            ->placeholder(Carbon::now())
                            ->columnSpan(3),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(UserTranslationKey::NAME)->sortable()->searchable(),
                TextColumn::makeTranslated(UserTranslationKey::EMAIL)->sortable()->searchable(),
                TextColumn::makeTranslated(UserTranslationKey::PHONE)->sortable()->searchable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            LoginHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}'),
        ];
    }

    /*
     * Policies
     * */

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}

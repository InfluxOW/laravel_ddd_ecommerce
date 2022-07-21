<?php

namespace App\Domains\Users\Admin\Resources;

use App\Components\Addressable\Admin\RelationManagers\AddressesRelationManager;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Users\Admin\Resources\UserResource\RelationManagers\UserLoginHistoryRelationManager;
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
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

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
                    ->schema(self::setTranslatableLabels([
                        Grid::make()
                            ->schema(self::setTranslatableLabels([
                                TextInput::make(UserTranslationKey::NAME->value)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('John Doe'),
                                TextInput::make(UserTranslationKey::EMAIL->value)
                                    ->required()
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('john_doe@gmail.com'),
                                TextInput::make(UserTranslationKey::PHONE->value)
                                    ->nullable()
                                    ->maxLength(18)
                                    ->mask(fn (TextInput\Mask $mask): TextInput\Mask => $mask->pattern('+0 (000) 000-00-00'))
                                    ->tel(),
                            ]))
                            ->columns(3),
                        TextInput::make(UserTranslationKey::PASSWORD->value)
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->password()
                            ->hidden(fn (Page $livewire): bool => $livewire instanceof ViewRecord)
                            ->dehydrated(fn (?string $state): bool => isset($state))
                            ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                            ->minLength(6)
                            ->maxLength(255)
                            ->placeholder('Password')
                            ->columnSpan(2),
                        DateTimePicker::make(UserTranslationKey::EMAIL_VERIFIED_AT->value)
                            ->nullable()
                            ->placeholder(Carbon::now())
                            ->columnSpan(2),
                    ]))
                    ->columnSpan(2),
                TimestampsCard::make()
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(UserTranslationKey::NAME->value)->sortable()->searchable(),
                TextColumn::make(UserTranslationKey::EMAIL->value)->sortable()->searchable(),
                TextColumn::make(UserTranslationKey::PHONE->value)->sortable()->searchable(),
            ]))
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            UserLoginHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Domains\Users\Admin\Resources\UserResource\Pages\ListUsers::route('/'),
            'edit' => \App\Domains\Users\Admin\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
            'view' => \App\Domains\Users\Admin\Resources\UserResource\Pages\ViewUser::route('/{record}'),
        ];
    }

    /*
     * Policies
     * */

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return UserTranslationKey::class;
    }
}

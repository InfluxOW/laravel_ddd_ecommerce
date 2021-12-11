<?php

namespace App\Domain\Users\Admin\Resources;

use App\Domain\Users\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    /** @param User $record */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'E-mail' => $record->email,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return route('filament.resources.users.view', ['record' => $record]);
    }

    /*
     * Data
     * */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->minValue(2)
                    ->maxLength(255)
                    ->placeholder('John Doe'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->placeholder('john_doe@gmail.com'),
                TextInput::make('phone')
                    ->nullable()
                    ->maxLength(18)
                    ->mask(fn (TextInput\Mask $mask): TextInput\Mask => $mask->pattern('+0 (000) 000-00-00'))
                    ->tel(),
                TextInput::make('password')
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->password()
                    ->hidden(fn (Page $livewire): bool => $livewire instanceof ViewRecord)
                    ->dehydrated(fn (?string $state): bool => isset($state))
                    ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                    ->minLength(6)
                    ->maxLength(255)
                    ->placeholder('Password'),
                DateTimePicker::make('email_verified_at')
                    ->nullable()
                    ->label('Email Verified At')
                    ->placeholder(Carbon::now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('phone')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Registered At')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'DESC');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Domain\Users\Admin\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Domain\Users\Admin\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Domain\Users\Admin\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
            'view' => \App\Domain\Users\Admin\Resources\UserResource\Pages\ViewUser::route('/{record}'),
        ];
    }
}

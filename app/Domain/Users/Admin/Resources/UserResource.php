<?php

namespace App\Domain\Users\Admin\Resources;

use App\Domain\Admin\Panel\Components\Cards\TimestampsCard;
use App\Domain\Users\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
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

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $label = 'Customer';

    protected static ?string $pluralLabel = 'Customers';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    /** @param User $record */
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
                Card::make()
                    ->schema([
                        Grid::make()
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
                            ])
                            ->columns(3),
                        TextInput::make('password')
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->password()
                            ->hidden(fn (Page $livewire): bool => $livewire instanceof ViewRecord)
                            ->dehydrated(fn (?string $state): bool => isset($state))
                            ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                            ->minLength(6)
                            ->maxLength(255)
                            ->placeholder('Password')
                            ->columnSpan(2),
                        DateTimePicker::make('email_verified_at')
                            ->nullable()
                            ->label('Email Verified At')
                            ->placeholder(Carbon::now())
                            ->columnSpan(2),
                    ])
                    ->columnSpan(2),
                TimestampsCard::make()
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('phone')->sortable()->searchable(),
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
            'edit' => \App\Domain\Users\Admin\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
            'view' => \App\Domain\Users\Admin\Resources\UserResource\Pages\ViewUser::route('/{record}'),
        ];
    }
}

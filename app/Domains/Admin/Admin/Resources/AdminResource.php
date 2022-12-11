<?php

namespace App\Domains\Admin\Admin\Resources;

use App\Components\LoginHistoryable\Admin\RelationManagers\LoginHistoryRelationManager;
use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Resources\AdminResource\Pages\CreateAdmin;
use App\Domains\Admin\Admin\Resources\AdminResource\Pages\EditAdmin;
use App\Domains\Admin\Admin\Resources\AdminResource\Pages\ListAdmins;
use App\Domains\Admin\Admin\Resources\AdminResource\Pages\ViewAdmin;
use App\Domains\Admin\Enums\Translation\Resources\AdminTranslationKey;
use App\Domains\Admin\Models\Admin;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

final class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'admins';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    /** @param  Admin  $record */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'E-mail' => $record->email,
        ];
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
                                TextInput::makeTranslated(AdminTranslationKey::NAME)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('John Doe')
                                    ->columnSpan(1),
                                TextInput::makeTranslated(AdminTranslationKey::EMAIL)
                                    ->required()
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('john_doe@gmail.com')
                                    ->columnSpan(1),
                            ])
                            ->columnSpan(2),
                        TextInput::makeTranslated(AdminTranslationKey::PASSWORD)
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->password()
                            ->hidden(fn (Page $livewire): bool => $livewire instanceof ViewRecord)
                            ->dehydrated(fn (?string $state): bool => isset($state))
                            ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                            ->minLength(6)
                            ->maxLength(255)
                            ->placeholder('Password')
                            ->columnSpan(2),
                    ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(AdminTranslationKey::NAME)->sortable()->searchable(),
                TextColumn::makeTranslated(AdminTranslationKey::EMAIL)->sortable()->searchable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LoginHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdmins::route('/'),
            'create' => CreateAdmin::route('/create'),
            'edit' => EditAdmin::route('/{record}/edit'),
            'view' => ViewAdmin::route('/{record}'),
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
}

<?php

namespace App\Domains\Feedback\Admin\Resources;

use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Actions\UpdateBulkAction;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\EditFeedback;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ListFeedback;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ViewFeedback;
use App\Domains\Feedback\Database\Builders\FeedbackBuilder;
use App\Domains\Feedback\Enums\Translation\FeedbackTranslationKey;
use App\Domains\Feedback\Models\Feedback;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ysfkaya\FilamentPhoneInput\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

final class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $slug = 'feedback';

    protected static ?string $navigationIcon = 'heroicon-o-mail';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['text', 'username', 'email', 'phone'];
    }

    /** @param  ?Feedback  $record */
    public static function getRecordTitle(?Model $record): ?string
    {
        return $record === null ? null : Str::limit($record->text);
    }

    /** @param  Feedback  $record */
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
                        Toggle::makeTranslated(FeedbackTranslationKey::IS_REVIEWED),
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(FeedbackTranslationKey::USERNAME)
                                    ->disabled(),
                                TextInput::makeTranslated(FeedbackTranslationKey::EMAIL)
                                    ->email()
                                    ->disabled(),
                                PhoneInput::makeTranslated(FeedbackTranslationKey::PHONE)
                                    ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                                    ->disabled(),
                            ])
                            ->columns(3),
                        Select::makeTranslated(FeedbackTranslationKey::USER)
                            ->relationship('user', 'name')
                            ->disabled(),
                        Textarea::makeTranslated(FeedbackTranslationKey::TEXT)
                            ->disabled(),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::makeTranslated(FeedbackTranslationKey::IS_REVIEWED)->boolean()->sortable(),
                TextColumn::makeTranslated(FeedbackTranslationKey::TEXT)->sortable()->searchable()->limit(70),
                TextColumn::makeTranslated(FeedbackTranslationKey::CREATED_AT)->sortable()->dateTime(),
            ])
            ->filters([
                TernaryFilter::makeTranslated(FeedbackTranslationKey::IS_REVIEWED)
                    ->queries(
                        true: fn (FeedbackBuilder $query) => $query->where('is_reviewed', true),
                        false: fn (FeedbackBuilder $query) => $query->where('is_reviewed', false),
                    ),
            ])->appendBulkActions([
                UpdateBulkAction::create()
                    ->action(function (Collection $records, array $data): void {
                        /** @phpstan-ignore-next-line */
                        $records->each(function (Feedback $feedback) use ($data): void {
                            $feedback->is_reviewed = $data[FeedbackTranslationKey::IS_REVIEWED->value];
                            $feedback->save();
                        });
                    })
                    ->form([
                        Toggle::makeTranslated(FeedbackTranslationKey::IS_REVIEWED)->required(),
                    ])
                    ->deselectRecordsAfterCompletion(),
            ]);
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
            'index' => ListFeedback::route('/'),
            'edit' => EditFeedback::route('/{record}/edit'),
            'view' => ViewFeedback::route('/{record}'),
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

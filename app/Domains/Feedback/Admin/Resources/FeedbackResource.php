<?php

namespace App\Domains\Feedback\Admin\Resources;

use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Actions\Tables\BulkUpdateAction;
use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Feedback\Enums\Translation\FeedbackResourceTranslationKey;
use App\Domains\Feedback\Models\Feedback;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /** @param ?Feedback $record */
    public static function getRecordTitle(?Model $record): ?string
    {
        return ($record === null) ? null : Str::limit($record->text);
    }

    /** @param Feedback $record */
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
                        Toggle::make(FeedbackResourceTranslationKey::IS_REVIEWED->value),
                        Grid::make()
                            ->schema(self::setTranslatableLabels([
                                TextInput::make(FeedbackResourceTranslationKey::USERNAME->value)
                                    ->disabled(),
                                TextInput::make(FeedbackResourceTranslationKey::EMAIL->value)
                                    ->email()
                                    ->disabled(),
                                TextInput::make(FeedbackResourceTranslationKey::PHONE->value)
                                    ->mask(fn (TextInput\Mask $mask): TextInput\Mask => $mask->pattern('+0 (000) 000-00-00'))
                                    ->tel()
                                    ->disabled(),
                            ]))
                            ->columns(3),
                        BelongsToSelect::make(FeedbackResourceTranslationKey::USER->value)
                            ->relationship('user', 'name')
                            ->disabled(),
                        Textarea::make(FeedbackResourceTranslationKey::TEXT->value)
                            ->disabled(),
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
                BooleanColumn::make(FeedbackResourceTranslationKey::IS_REVIEWED->value)->sortable(),
                TextColumn::make(FeedbackResourceTranslationKey::TEXT->value)->sortable()->searchable()->limit(70),
                TextColumn::make(FeedbackResourceTranslationKey::CREATED_AT->value)->sortable()->dateTime(),
            ]))
            ->filters(self::setTranslatableLabels([
                TernaryFilter::make(FeedbackResourceTranslationKey::IS_REVIEWED->value)
                    ->queries(
                        true: fn (Builder $query): Builder => $query->where('is_reviewed', true),
                        false: fn (Builder $query): Builder => $query->where('is_reviewed', false),
                    ),
            ]))->appendBulkActions([
                BulkUpdateAction::create()
                    ->action(function (Collection $records, array $data): void {
                        $records->each(function (Feedback $feedback) use ($data): void {
                            $feedback->is_reviewed = $data[FeedbackResourceTranslationKey::IS_REVIEWED->value];
                            $feedback->save();
                        });
                    })
                    ->form(self::setTranslatableLabels([
                        Toggle::make(FeedbackResourceTranslationKey::IS_REVIEWED->value)->required(),
                    ]))
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
            'index' => \App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ListFeedback::route('/'),
            'edit' => \App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\EditFeedback::route('/{record}/edit'),
            'view' => \App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ViewFeedback::route('/{record}'),
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
        return FeedbackResourceTranslationKey::class;
    }
}

<?php

namespace App\Domains\Feedback\Admin\Resources;

use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use App\Domains\Feedback\Enums\Translation\FeedbackResourceTranslationKey;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Providers\DomainServiceProvider;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;
    protected static ?string $slug = 'feedback';
    protected static ?string $navigationIcon = 'heroicon-o-mail';

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
                                TextInput::make(FeedbackResourceTranslationKey::USERNAME->value),
                                TextInput::make(FeedbackResourceTranslationKey::EMAIL->value)
                                    ->email(),
                                TextInput::make(FeedbackResourceTranslationKey::PHONE->value)
                                    ->mask(fn (TextInput\Mask $mask): TextInput\Mask => $mask->pattern('+0 (000) 000-00-00'))
                                    ->tel(),
                            ]))
                            ->columns(3),
                        BelongsToSelect::make(FeedbackResourceTranslationKey::USER->value)
                            ->relationship('user', 'name'),
                        Textarea::make(FeedbackResourceTranslationKey::TEXT->value),
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
                TextColumn::make(FeedbackResourceTranslationKey::USERNAME->value)->sortable()->searchable(),
                TextColumn::make(FeedbackResourceTranslationKey::EMAIL->value)->sortable()->searchable(),
                TextColumn::make(FeedbackResourceTranslationKey::PHONE->value)->sortable()->searchable(),
                TextColumn::make(FeedbackResourceTranslationKey::TEXT->value)->sortable()->searchable()->limit(),
            ]))
            ->filters([
                //
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
            'view' => \App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ViewFeedback::route('/{record}'),
        ];
    }

    /*
     * Translation
     * */

    protected static function getTranslationNamespace(): TranslationNamespace
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE;
    }

    protected static function getTranslationKeyClass(): string
    {
        return FeedbackResourceTranslationKey::class;
    }
}

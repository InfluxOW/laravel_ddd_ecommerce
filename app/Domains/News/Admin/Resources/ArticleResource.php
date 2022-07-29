<?php

namespace App\Domains\News\Admin\Resources;

use App\Components\Mediable\Admin\Components\Fields\MediaLibraryFileUpload;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Forms\RichEditor;
use App\Domains\Catalog\Enums\Media\ProductCategoryMediaCollectionKey;
use App\Domains\News\Enums\Translation\ArticleTranslationKey;
use App\Domains\News\Models\Article;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'news';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'body'];
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
                        DateTimePicker::makeTranslated(ArticleTranslationKey::PUBLISHED_AT)
                            ->nullable()
                            ->columnSpan(3),
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(ArticleTranslationKey::TITLE)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set, $state): mixed => $set(ArticleTranslationKey::SLUG->value, Str::slug($state)))
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('Semi Truck Hauling Beer Collapses, Causing Traffic Mess On I-76')
                                    ->columnSpan(5),
                                TextInput::makeTranslated(ArticleTranslationKey::SLUG)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('beer-truck-collapse')
                                    ->columnSpan(3),
                            ])
                            ->columns(8)
                            ->columnSpan(3),
                        Textarea::makeTranslated(ArticleTranslationKey::DESCRIPTION)
                            ->required()
                            ->columnSpan(3),
                        RichEditor::makeTranslated(ArticleTranslationKey::BODY)
                            ->required()
                            ->columnSpan(3),
                        MediaLibraryFileUpload::makeTranslated(ArticleTranslationKey::IMAGES)
                            ->collection(ProductCategoryMediaCollectionKey::IMAGES->value)
                            ->multiple()
                            ->minFiles(0)
                            ->maxFiles(10)
                            ->image()
                            ->preserveFilenames()
                            ->enableReordering()
                            ->columnSpan(3),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(ArticleTranslationKey::TITLE)->sortable()->searchable()->limit(70),
                TextColumn::makeTranslated(ArticleTranslationKey::DESCRIPTION)->sortable()->searchable()->limit(70),
                TextColumn::makeTranslated(ArticleTranslationKey::PUBLISHED_AT)->sortable()->dateTime(),
            ])
            ->filters([
                TernaryFilter::makeTranslated(ArticleTranslationKey::IS_PUBLISHED)
                    ->queries(
                        /** @phpstan-ignore-next-line */
                        true: fn (Builder|Article $query): Builder => $query->published(),
                        /** @phpstan-ignore-next-line */
                        false: fn (Builder|Article $query): Builder => $query->unpublished(),
                    ),
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
            'index' => \App\Domains\News\Admin\Resources\ArticleResource\Pages\ListArticles::route('/'),
            'create' => \App\Domains\News\Admin\Resources\ArticleResource\Pages\CreateArticle::route('/create'),
            'edit' => \App\Domains\News\Admin\Resources\ArticleResource\Pages\EditArticle::route('/{record}/edit'),
            'view' => \App\Domains\News\Admin\Resources\ArticleResource\Pages\ViewArticle::route('/{record}'),
        ];
    }
}

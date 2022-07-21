<?php

namespace App\Domains\Catalog\Admin\Resources;

use App\Components\Mediable\Admin\Components\Fields\MediaLibraryFileUpload;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Catalog\Admin\Resources\ProductResource\RelationManagers\ProductAttributeValuesRelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource\RelationManagers\ProductPricesRelationManager;
use App\Domains\Catalog\Enums\Media\ProductMediaCollectionKey;
use App\Domains\Catalog\Enums\Translation\ProductTranslationKey;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'catalog/products';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    /*
     * Data
     * */

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::setTranslatableLabels([
                Card::make()
                    ->schema(self::setTranslatableLabels([
                        Toggle::make(ProductTranslationKey::IS_VISIBLE->value)
                            ->columnSpan(1),
                        Toggle::make(ProductTranslationKey::IS_DISPLAYABLE->value)
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make(ProductTranslationKey::TITLE->value)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, $state): mixed => $set(ProductTranslationKey::SLUG->value, Str::slug($state)))
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('TV')
                            ->columnSpan(1),
                        TextInput::make(ProductTranslationKey::SLUG->value)
                            ->required()
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('tv')
                            ->columnSpan(1),
                        MarkdownEditor::make(ProductTranslationKey::DESCRIPTION->value)
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->columnSpan(2),
                    ]))
                    ->columnSpan(2),
                TimestampsCard::make()
                    ->columnSpan(1),
                Card::make()
                    ->columnSpan(3)
                    ->schema(self::setTranslatableLabels([
                        MultiSelect::make(ProductTranslationKey::CATEGORIES->value)
                            ->relationship('categories', 'title')
                            ->options(fn (?Product $record, callable $get): array => ProductCategory::query()
                                ->hasLimitedDepth()
                                ->where('depth', '>=', ProductCategory::MAX_DEPTH - 1)
                                ->whereIntegerNotInRaw('id', $get(ProductTranslationKey::CATEGORIES->value))
                                ->orderBy('left')
                                ->pluck('title', 'id')
                                ->toArray()),
                    ])),
                MediaLibraryFileUpload::make(ProductTranslationKey::IMAGES->value)
                    ->collection(ProductMediaCollectionKey::IMAGES->value)
                    ->multiple()
                    ->minFiles(1)
                    ->maxFiles(10)
                    ->image()
                    ->preserveFilenames()
                    ->enableReordering()
                    ->columnSpan(3),
            ]))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(ProductTranslationKey::TITLE->value)->sortable()->searchable(),
                TextColumn::make(ProductTranslationKey::SLUG->value)->searchable(),
            ]))
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductAttributeValuesRelationManager::class,
            ProductPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Domains\Catalog\Admin\Resources\ProductResource\Pages\ListProducts::route('/'),
            'create' => \App\Domains\Catalog\Admin\Resources\ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => \App\Domains\Catalog\Admin\Resources\ProductResource\Pages\EditProduct::route('/{record}/edit'),
            'view' => \App\Domains\Catalog\Admin\Resources\ProductResource\Pages\ViewProduct::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['attributeValues.attribute', 'prices', 'media.model']);
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return ProductTranslationKey::class;
    }
}

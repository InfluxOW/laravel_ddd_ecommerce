<?php

namespace App\Domains\Catalog\Admin\Resources;

use App\Components\Attributable\Admin\RelationManagers\AttributeValuesRelationManager;
use App\Components\Mediable\Admin\Components\Fields\MediaLibraryFileUpload;
use App\Components\Purchasable\Admin\RelationManagers\PricesRelationManager;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Forms\RichEditor;
use App\Domains\Catalog\Enums\Media\ProductMediaCollectionKey;
use App\Domains\Catalog\Enums\Translation\ProductTranslationKey;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
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
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                Toggle::makeTranslated(ProductTranslationKey::IS_VISIBLE)
                                    ->columnSpan(2),
                                Toggle::makeTranslated(ProductTranslationKey::IS_DISPLAYABLE)
                                    ->disabled()
                                    ->columnSpan(3),
                            ])
                            ->columns(10)
                            ->columnSpan(2),
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(ProductTranslationKey::TITLE)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set, $state): mixed => $set(ProductTranslationKey::SLUG->value, Str::slug($state)))
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('TV')
                                    ->columnSpan(5),
                                TextInput::makeTranslated(ProductTranslationKey::SLUG)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('tv')
                                    ->columnSpan(3),
                            ])
                            ->columns(8)
                            ->columnSpan(2),
                        RichEditor::makeTranslated(ProductTranslationKey::DESCRIPTION)
                            ->required()
                            ->columnSpan(2),
                        MultiSelect::makeTranslated(ProductTranslationKey::CATEGORIES)
                            ->relationship('categories', 'title')
                            ->options(fn (?Product $record, callable $get): array => ProductCategory::query()
                                ->hasLimitedDepth()
                                ->where('depth', '>=', ProductCategory::MAX_DEPTH - 1)
                                ->whereIntegerNotInRaw('id', $get(ProductTranslationKey::CATEGORIES->value))
                                ->orderBy('left')
                                ->pluck('title', 'id')
                                ->toArray())
                            ->columnSpan(2),
                        MediaLibraryFileUpload::makeTranslated(ProductTranslationKey::IMAGES)
                            ->collection(ProductMediaCollectionKey::IMAGES->value)
                            ->multiple()
                            ->minFiles(0)
                            ->maxFiles(10)
                            ->image()
                            ->preserveFilenames()
                            ->enableReordering()
                            ->columnSpan(2),
                    ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(ProductTranslationKey::TITLE)->sortable()->searchable(),
                TextColumn::makeTranslated(ProductTranslationKey::SLUG)->searchable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AttributeValuesRelationManager::class,
            PricesRelationManager::class,
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
}

<?php

namespace App\Domains\Catalog\Admin\Resources;

use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use App\Domains\Catalog\Admin\Resources\ProductResource\RelationManagers\ProductAttributeValuesRelationManager;
use App\Domains\Catalog\Admin\Resources\ProductResource\RelationManagers\ProductPricesRelationManager;
use App\Domains\Catalog\Enums\Translation\ProductResourceTranslationKey;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    use TranslatableAdminResource;
    use HasTranslatableAdminLabels;

    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'catalog/products';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 0;

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return route(sprintf('filament.resources.%s.view', static::$slug), ['record' => $record]);
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
                        TextInput::make(ProductResourceTranslationKey::TITLE->value)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, $state): mixed => $set(ProductResourceTranslationKey::SLUG->value, Str::slug($state)))
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('TV'),
                        TextInput::make(ProductResourceTranslationKey::SLUG->value)
                            ->required()
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('tv'),
                        MarkdownEditor::make(ProductResourceTranslationKey::DESCRIPTION->value)
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
                    ->schema([
                        BelongsToManyMultiSelect::make(ProductResourceTranslationKey::CATEGORIES->value)
                            ->relationship('categories', 'title')
                            ->options(fn (?Product $record, callable $get): array => ProductCategory::query()
                                    ->hasLimitedDepth()
                                    ->where('depth', '>=', ProductCategory::MAX_DEPTH - 1)
                                    ->whereIntegerNotInRaw('id', $get(ProductResourceTranslationKey::CATEGORIES->value))
                                    ->orderBy('left')
                                    ->pluck('title', 'id')
                                    ->toArray()),
                    ]),
            ]))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(ProductResourceTranslationKey::TITLE->value)->sortable()->searchable(),
                TextColumn::make(ProductResourceTranslationKey::SLUG->value)->searchable(),
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
        return parent::getEloquentQuery()->with(['attributeValues.attribute', 'prices']);
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return ProductResourceTranslationKey::class;
    }

    protected static function getTranslationNamespace(): TranslationNamespace
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE;
    }
}

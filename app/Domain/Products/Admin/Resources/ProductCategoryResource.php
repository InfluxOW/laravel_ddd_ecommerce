<?php

namespace App\Domain\Products\Admin\Resources;

use App\Domain\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domain\Admin\Traits\Translation\TranslatableAdminResource;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Products\Admin\Resources\ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager;
use App\Domain\Products\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domain\Products\Models\ProductCategory;
use App\Domain\Products\Providers\DomainServiceProvider;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategoryResource extends Resource
{
    use TranslatableAdminResource;

    protected static ?string $model = ProductCategory::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 2;

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return route('filament.resources.product-categories.view', ['record' => $record]);
    }

    /*
     * Data
     * */

    public static function form(Form $form): Form
    {
        ProductCategory::loadHierarchy();

        return $form
            ->schema([
                Tabs::make('_')
                    ->columns(3)
                    ->columnSpan(2)
                    ->tabs([
                        Tabs\Tab::make(self::translateEnum(ProductCategoryResourceTranslationKey::MAIN))
                            ->columns(3)
                            ->schema(self::setTranslatableLabels([
                                Card::make()
                                    ->schema(self::getCreationFormSchema())
                                    ->columnSpan(2),
                                TimestampsCard::make()
                                    ->columnSpan(1),
                                Placeholder::make(ProductCategoryResourceTranslationKey::PATH->value)
                                    ->content(fn (?ProductCategory $record): string => ($record === null || $record->path === '') ? '-' : $record->path)
                                    ->columnSpan(2),
                            ])),
                        Tabs\Tab::make(self::translateEnum(ProductCategoryResourceTranslationKey::STATISTICS))
                            ->schema([
                                Grid::make()
                                    ->schema(self::setTranslatableLabels([
                                        Placeholder::make('overall_products_count')
                                            ->label('Products Count Including Descendants')
                                            ->content(fn (?ProductCategory $record): int => ($record === null) ? 0 : $record->overall_products_count),
                                        Placeholder::make('products_count')
                                            ->label('Products Count')
                                            ->content(fn (?ProductCategory $record): ?int => ($record === null) ? 0 : ProductCategory::findInHierarchy($record->id)?->products_count),
                                    ]))
                                    ->columns(4),
                            ]),
                    ]),
            ]);
    }

    public static function getCreationFormSchema(): array
    {
        return self::setTranslatableLabels([
            TextInput::make(ProductCategoryResourceTranslationKey::TITLE->value)
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set, $state): mixed => $set(ProductCategoryResourceTranslationKey::SLUG->value, Str::slug($state)))
                ->minValue(2)
                ->maxLength(255)
                ->placeholder('Electronics'),
            TextInput::make(ProductCategoryResourceTranslationKey::SLUG->value)
                ->required()
                ->minValue(2)
                ->maxLength(255)
                ->placeholder('electronics'),
            BelongsToSelect::make(ProductCategoryResourceTranslationKey::PARENT_ID->value)
                ->relationship('parent', 'title')
                ->options(function (?Model $record, Page|RelationManager $livewire): array {
                    if ($livewire instanceof CreateRecord) {
                        $categories = ProductCategory::query()->orderBy('left')->get();
                    } elseif ($livewire instanceof RelationManager) {
                        $categories = collect([$livewire->ownerRecord]);
                    } else {
                        $categories = ProductCategory::query()->orderBy('left')->withoutNode($record)->get()->filter(fn (ProductCategory $parent) => ! $parent->insideSubtree($record));
                    }

                    return $categories->pluck('title', 'id')->toArray();
                })
                ->disabled(fn (Page|RelationManager $livewire): bool => $livewire instanceof RelationManager)
                ->default(fn (Page|RelationManager $livewire): ?int => ($livewire instanceof RelationManager && isset($livewire->ownerRecord->id)) ? $livewire->ownerRecord->id : null)
                ->searchable(fn (Page|RelationManager $livewire) => $livewire instanceof Page)
                ->columnSpan(2),
        ]);
    }

    public static function getTableColumns(): array
    {
        return self::setTranslatableLabels([
            TextColumn::make(ProductCategoryResourceTranslationKey::LEFT->value)->sortable(),
            TextColumn::make(ProductCategoryResourceTranslationKey::TITLE->value)->sortable()->searchable(),
            TextColumn::make(ProductCategoryResourceTranslationKey::SLUG->value)->searchable(),
            TextColumn::make(ProductCategoryResourceTranslationKey::PARENT_TITLE->value)->sortable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters(self::setTranslatableLabels([
                SelectFilter::make(ProductCategoryResourceTranslationKey::DEPTH->value)->options(ProductCategory::query()->orderBy('depth')->distinct('depth')->pluck('depth', 'depth')),
            ]))
            ->defaultSort(ProductCategoryResourceTranslationKey::LEFT->value, 'ASC');
    }

    public static function getRelations(): array
    {
        return [
            ProductCategoryChildrenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories::route('/'),
            'create' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\CreateProductCategory::route('/create'),
            'edit' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\EditProductCategory::route('/{record}/edit'),
            'view' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\ViewProductCategory::route('/{record}'),
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
        return ProductCategoryResourceTranslationKey::class;
    }
}

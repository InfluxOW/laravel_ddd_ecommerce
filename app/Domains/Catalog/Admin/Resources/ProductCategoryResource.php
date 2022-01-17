<?php

namespace App\Domains\Catalog\Admin\Resources;

use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager;
use App\Domains\Catalog\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $slug = 'catalog/categories';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                                            ->content(fn (?ProductCategory $record): ?int => ($record === null) ? 0 : ProductCategory::findInHierarchy($record->id, ProductCategory::$hierarchy)?->products_count),
                                    ]))
                                    ->columns(4),
                            ]),
                    ]),
            ]);
    }

    public static function getCreationFormSchema(): array
    {
        return self::setTranslatableLabels([
            Toggle::make(ProductCategoryResourceTranslationKey::IS_VISIBLE->value)
                ->columnSpan(2),
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
            MarkdownEditor::make(ProductCategoryResourceTranslationKey::DESCRIPTION->value)
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->columnSpan(2),
            BelongsToSelect::make(ProductCategoryResourceTranslationKey::PARENT_ID->value)
                ->relationship('parent', 'title')
                ->options(function (?Model $record, Page|RelationManager $livewire): array {
                    if ($livewire instanceof CreateRecord) {
                        $categories = ProductCategory::query()->hasLimitedDepth()->orderBy('left')->get();
                    } elseif ($livewire instanceof RelationManager) {
                        $categories = collect([$livewire->ownerRecord]);
                    } else {
                        $categories = ProductCategory::query()->hasLimitedDepth()->orderBy('left')->withoutNode($record)->get()->filter(fn (ProductCategory $parent) => ! $parent->insideSubtree($record));
                    }

                    return $categories->pluck('title', 'id')->toArray();
                })
                ->disabled(fn (Page|RelationManager $livewire): bool => $livewire instanceof RelationManager)
                ->default(fn (Page|RelationManager $livewire): ?int => ($livewire instanceof RelationManager && isset($livewire->ownerRecord->id)) ? $livewire->ownerRecord->id : null)
                ->searchable(fn (Page|RelationManager $livewire) => $livewire instanceof Page)
                ->reactive()
                ->afterStateUpdated(function (callable $set, callable $get): void {
                    $parentId = $get(ProductCategoryResourceTranslationKey::PARENT_ID->value);
                    $parent = ($parentId === null) ? null : ProductCategory::query()->hasLimitedDepth()->find($parentId);

                    if (isset($parent->depth)) {
                        $set(ProductCategoryResourceTranslationKey::DEPTH->value, $parent->depth + 1);
                    }
                })
                ->columnSpan(2),
            TextInput::make(ProductCategoryResourceTranslationKey::DEPTH->value)
                ->disabled()
                ->default(fn (Page|RelationManager $livewire): ?int => ($livewire instanceof RelationManager && isset($livewire->ownerRecord->depth)) ? (int) ($livewire->ownerRecord->depth + 1) : null)
                ->lte((string) ProductCategory::MAX_DEPTH, true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(ProductCategoryResourceTranslationKey::LEFT->value)->sortable(),
                TextColumn::make(ProductCategoryResourceTranslationKey::TITLE->value)->sortable()->searchable(),
                TextColumn::make(ProductCategoryResourceTranslationKey::SLUG->value)->searchable(),
                TextColumn::make(ProductCategoryResourceTranslationKey::PARENT_TITLE->value)->sortable(),
            ]))
            ->filters(self::setTranslatableLabels([
                SelectFilter::make(ProductCategoryResourceTranslationKey::DEPTH->value)->options(ProductCategory::query()->hasLimitedDepth()->orderBy('depth')->distinct('depth')->pluck('depth', 'depth')),
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
            'index' => \App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories::route('/'),
            'create' => \App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\CreateProductCategory::route('/create'),
            'edit' => \App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\EditProductCategory::route('/{record}/edit'),
            'view' => \App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ViewProductCategory::route('/{record}'),
        ];
    }

    /*
     * Policies
     * */

    /**
     * @param ProductCategory $record
     * @return bool
     */
    public static function canDelete(Model $record): bool
    {
        return $record->overall_products_count === 0;
    }

    public static function canDeleteAny(): bool
    {
        return false;
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

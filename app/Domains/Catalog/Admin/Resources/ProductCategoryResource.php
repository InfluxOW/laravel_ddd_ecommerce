<?php

namespace App\Domains\Catalog\Admin\Resources;

use App\Components\Mediable\Admin\Components\Fields\MediaLibraryFileUpload;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Admin\Components\Forms\RichEditor;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\CreateProductCategory;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\EditProductCategory;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\HierarchyProductCategory;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ViewProductCategory;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers\ProductCategoryChildrenRelationManager;
use App\Domains\Catalog\Enums\Media\ProductCategoryMediaCollectionKey;
use App\Domains\Catalog\Enums\Translation\ProductCategoryTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Common\Utils\LangUtils;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class ProductCategoryResource extends Resource
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
                                Toggle::makeTranslated(ProductCategoryTranslationKey::IS_VISIBLE)
                                    ->columnSpan(2),
                                Toggle::makeTranslated(ProductCategoryTranslationKey::IS_DISPLAYABLE)
                                    ->disabled()
                                    ->columnSpan(3),
                            ])
                            ->columns(10)
                            ->columnSpan(2),
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(ProductCategoryTranslationKey::TITLE)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get, $state, ?ProductCategory $record, Page|RelationManager $livewire): void {
                                        $path = $record?->parent?->path;
                                        $depth = $record?->depth;

                                        if ($livewire instanceof RelationManager && $record === null) {
                                            /** @var ProductCategory $category */
                                            $category = $livewire->getOwnerRecord();
                                            $path = $category->path;
                                            $depth = $category->getDepth();
                                        }

                                        $path = match (true) {
                                            $depth > 0 => isset($state) ? "{$path} — {$state}" : $path,
                                            $depth === 0 => $state,
                                            default => $path,
                                        };

                                        $set(ProductCategoryTranslationKey::SLUG->value, Str::slug($state));
                                        $set(ProductCategoryTranslationKey::PATH->value, $path);
                                    })
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('Electronics')
                                    ->columnSpan(5),
                                TextInput::makeTranslated(ProductCategoryTranslationKey::SLUG)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('electronics')
                                    ->columnSpan(3),
                            ])
                            ->columns(8)
                            ->columnSpan(2),
                        RichEditor::makeTranslated(ProductCategoryTranslationKey::DESCRIPTION)->columnSpan(2),
                        Select::makeTranslated(ProductCategoryTranslationKey::PARENT_ID)
                            ->relationship('parent', 'title')
                            ->disabled()
                            ->default(fn (Page|RelationManager $livewire): ?int => $livewire instanceof RelationManager ? $livewire->getOwnerRecord()->getKey() : null)
                            ->columnSpan(2),
                        MediaLibraryFileUpload::makeTranslated(ProductCategoryTranslationKey::IMAGES)
                            ->collection(ProductCategoryMediaCollectionKey::IMAGES->value)
                            ->multiple()
                            ->minFiles(0)
                            ->maxFiles(3)
                            ->image()
                            ->preserveFilenames()
                            ->enableReordering()
                            ->columnSpan(2),
                    ]),
                Section::makeTranslated(ProductCategoryTranslationKey::ADDITIONAL)
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(ProductCategoryTranslationKey::DEPTH)
                                    ->disabled()
                                    ->default(fn (Page|RelationManager $livewire): int => $livewire instanceof RelationManager && isset($livewire->getOwnerRecord()->depth) ? $livewire->getOwnerRecord()->depth + 1 : 0)
                                    ->lte((string) ProductCategory::MAX_DEPTH, true)
                                    ->columnSpan(2),
                                TextInput::makeTranslated(ProductCategoryTranslationKey::PATH)
                                    ->disabled()
                                    ->default(fn (Page|RelationManager $livewire): ?string => $livewire instanceof RelationManager && isset($livewire->getOwnerRecord()->path) ? $livewire->getOwnerRecord()->path : null)
                                    ->columnSpan(8),
                            ])
                            ->columns(10)
                            ->columnSpan(1),
                    ])
                    ->columnSpan(2),
            ])
            ->columns(2);
    }

    public static function viewingForm(Form $form): Form
    {
        /** @var string $mainTabTitle */
        $mainTabTitle = LangUtils::translateEnum(ProductCategoryTranslationKey::MAIN);
        /** @var string $statisticsTabTitle */
        $statisticsTabTitle = LangUtils::translateEnum(ProductCategoryTranslationKey::STATISTICS);

        $form = parent::viewingForm($form);

        return Form::make()
            ->schema([
                Tabs::make('_')
                    ->columns(3)
                    ->columnSpan(2)
                    ->tabs([
                        Tab::make($mainTabTitle)
                            ->columns(3)
                            ->schema([
                                Grid::make()
                                    ->schema($form->getSchema())
                                    ->columnSpan(3),
                            ]),
                        Tab::make($statisticsTabTitle)
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        Placeholder::makeTranslated(ProductCategoryTranslationKey::OVERALL_PRODUCTS_COUNT)
                                            ->content(fn (?ProductCategory $record): int => $record === null ? 0 : $record->overall_products_count),
                                        Placeholder::makeTranslated(ProductCategoryTranslationKey::PRODUCTS_COUNT)
                                            ->content(fn (?ProductCategory $record): ?int => $record === null ? 0 : ProductCategory::findInHierarchy($record->id, ProductCategory::getHierarchy())?->products_count),
                                    ])
                                    ->columns(4),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(ProductCategoryTranslationKey::TITLE)->sortable()->searchable(),
                TextColumn::makeTranslated(ProductCategoryTranslationKey::SLUG)->searchable(),
                TextColumn::makeTranslated(ProductCategoryTranslationKey::PARENT_TITLE)->sortable(),
            ])
            ->filters([
                SelectFilter::makeTranslated(ProductCategoryTranslationKey::DEPTH)->options(ProductCategory::query()->hasLimitedDepth()->orderBy('depth')->distinct('depth')->pluck('depth', 'depth'))->attribute('depth'),
            ])
            ->defaultSort(ProductCategoryTranslationKey::LEFT->value, 'ASC');
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
            'index' => ListProductCategories::route('/'),
            'hierarchy' => HierarchyProductCategory::route('/hierarchy'),
            'create' => CreateProductCategory::route('/create'),
            'edit' => EditProductCategory::route('/{record}/edit'),
            'view' => ViewProductCategory::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['media.model']);
    }

    /*
     * Policies
     * */

    /**
     * @param ProductCategory $record
     */
    public static function canDelete(Model $record): bool
    {
        return $record->overall_products_count === 0;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}

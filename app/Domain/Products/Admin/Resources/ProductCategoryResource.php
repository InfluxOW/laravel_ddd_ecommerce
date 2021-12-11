<?php

namespace App\Domain\Products\Admin\Resources;

use App\Domain\Products\Models\ProductCategory;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'title';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
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
        return $form
            ->schema([
                BelongsToSelect::make('parent_id')
                    ->relationship('parent', 'title')
                    ->options(function (callable $get): array {
                        $category = ProductCategory::query()->find($get('id'));
                        $categories = ProductCategory::query()->orderBy('left')->withoutNode($category)->get()->filter(fn (ProductCategory $parent) => ! $parent->insideSubtree($category));

                        return $categories->pluck('table_title', 'id')->toArray();
                    }),
                TextInput::make('path')
                    ->disabled(),
                TextInput::make('title')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, $state): mixed => $set('slug', Str::slug($state)))
                    ->minValue(2)
                    ->maxLength(255)
                    ->placeholder('Electronics'),
                TextInput::make('slug')
                    ->required()
                    ->minValue(2)
                    ->maxLength(255)
                    ->placeholder('electronics'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('left')->label('Position'),
                TextColumn::make('table_title')->label('Title')->searchable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('overall_products_count')->label('Products Count'),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('depth')->options(ProductCategory::query()->orderBy('depth')->distinct('depth')->pluck('depth', 'depth')),
            ])
            ->defaultSort('left');
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
            'index' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories::route('/'),
            'create' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\CreateProductCategory::route('/create'),
            'edit' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\EditProductCategory::route('/{record}/edit'),
            'view' => \App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages\ViewProductCategory::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        ProductCategory::loadHierarchy();

        return parent::getEloquentQuery()->orderBy('left')->with(['parent']);
    }
}

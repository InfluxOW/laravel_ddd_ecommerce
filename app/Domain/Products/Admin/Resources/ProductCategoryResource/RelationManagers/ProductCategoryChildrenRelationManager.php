<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use App\Domain\Products\Models\ProductCategory;
use Baum\Node;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ProductCategoryChildrenRelationManager extends HasManyRelationManager
{
    protected static ?string $label = 'Child';

    protected static string $relationship = 'children';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        ProductCategory::loadHierarchy();

        return $form
            ->schema(ProductCategoryResource::creationFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->prependActions([
                LinkAction::make('view')
                    ->label('View')
                    ->url(fn (ProductCategory $record): string => route('filament.resources.product-categories.view', $record))
                    ->color('success'),
            ])
            ->pushActions([
                LinkAction::make(' | '),
                ButtonAction::make('↑')
                    ->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->min('left')) ? $record : $record->moveLeft()),
                ButtonAction::make('↓')
                    ->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->max('left')) ? $record : $record->moveRight()),
            ])
            ->columns([
                TextColumn::make('left')->label('Position'),
                TextColumn::make('title')->searchable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('parent.title'),
            ])
            ->filters([
                //
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->orderBy('left');
    }
}

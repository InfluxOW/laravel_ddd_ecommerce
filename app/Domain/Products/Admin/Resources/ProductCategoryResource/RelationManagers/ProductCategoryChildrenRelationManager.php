<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domain\Admin\Admin\Components\Actions\ViewAction;
use App\Domain\Admin\Traits\Translation\TranslatableAdminRelation;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use App\Domain\Products\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domain\Products\Models\ProductCategory;
use App\Domain\Products\Providers\DomainServiceProvider;
use Baum\Node;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Builder;

class ProductCategoryChildrenRelationManager extends HasManyRelationManager
{
    use TranslatableAdminRelation;

    protected static string $relationship = 'children';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        ProductCategory::loadHierarchy();

        return $form
            ->schema(ProductCategoryResource::getCreationFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->prependActions([
                ViewAction::create()->url(fn (ProductCategory $record): string => route('filament.resources.product-categories.view', $record)),
            ])
            ->pushActions([
                LinkAction::make(' | '),
                ButtonAction::make('↑')->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->min('left')) ? $record : $record->moveLeft()),
                ButtonAction::make('↓')->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->max('left')) ? $record : $record->moveRight()),
            ])
            ->columns(ProductCategoryResource::getTableColumns())
            ->filters([
                //
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->orderBy('left');
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

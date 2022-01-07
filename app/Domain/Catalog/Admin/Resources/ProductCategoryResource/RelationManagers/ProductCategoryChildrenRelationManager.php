<?php

namespace App\Domain\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domain\Admin\Admin\Components\Actions\ViewAction;
use App\Domain\Admin\Traits\Translation\TranslatableAdminRelation;
use App\Domain\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domain\Catalog\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domain\Catalog\Models\ProductCategory;
use App\Domain\Catalog\Providers\DomainServiceProvider;
use App\Domain\Generic\Enums\Lang\TranslationNamespace;
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
    use TranslatableAdminRelation;

    protected static string $relationship = 'children';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        ProductCategory::loadHeavyHierarchy();

        return $form->schema(ProductCategoryResource::getCreationFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->prependActions([
                ViewAction::create()->url(fn (ProductCategory $record): string => route('filament.resources.catalog/categories.view', $record)),
            ])
            ->pushActions([
                LinkAction::make(' | '),
                ButtonAction::make('↑')->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->min('left')) ? $record : $record->moveLeft()),
                ButtonAction::make('↓')->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->max('left')) ? $record : $record->moveRight()),
            ])
            ->columns(self::setTranslatableLabels([
                TextColumn::make(ProductCategoryResourceTranslationKey::LEFT->value),
                TextColumn::make(ProductCategoryResourceTranslationKey::TITLE->value)->searchable(),
                TextColumn::make(ProductCategoryResourceTranslationKey::SLUG->value)->searchable(),
            ]))
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

<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domains\Admin\Admin\Components\Actions\ViewAction;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminRelation;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use Baum\Node;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ProductCategoryChildrenRelationManager extends HasManyRelationManager
{
    use TranslatableAdminRelation;
    use HasTranslatableAdminLabels;

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
     * Policies
     * */

    protected function canCreate(): bool
    {
        /** @var ProductCategory $category */
        $category = $this->ownerRecord;

        return $this->shouldBeDisplayed() && $category->depth < ProductCategory::MAX_DEPTH;
    }

    protected function canDeleteAny(): bool
    {
        return $this->shouldBeDisplayed() && ProductCategoryResource::canDeleteAny();
    }

    /** @param ProductCategory $record */
    protected function canDelete(Model $record): bool
    {
        return $this->shouldBeDisplayed() && ProductCategoryResource::canDelete($record);
    }

    /** @param ProductCategory $record */
    protected function canEdit(Model $record): bool
    {
        return $this->shouldBeDisplayed();
    }

    private function shouldBeDisplayed(): bool
    {
        return collect([
            ProductCategoryResource::getUrl('view', $this->ownerRecord->id),
            route('livewire.message', ['catalog.admin.resources.product-category-resource.pages.view-product-category']),
        ])->doesntContain(Request::url());
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

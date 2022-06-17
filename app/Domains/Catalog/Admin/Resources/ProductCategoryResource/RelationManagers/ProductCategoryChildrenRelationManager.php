<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManagers\HasManyRelationManager;
use App\Domains\Admin\Admin\Components\Actions\ViewAction;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Enums\Translation\ProductCategoryResourceTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use Baum\Node;
use Filament\Resources\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class ProductCategoryChildrenRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'children';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(ProductCategoryResource::getCreationFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->prependActions([
                ViewAction::create()->url(fn (ProductCategory $record): string => route('filament.resources.catalog/categories.view', $record)),
            ])
            ->appendActions([
                /* @phpstan-ignore-next-line */
                Action::make(' | ')->visible(fn (Page|RelationManager $livewire, ProductCategory $record) => $livewire->canEdit($record)),
                /* @phpstan-ignore-next-line */
                Action::make('↑')->button()->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->min('left')) ? $record : $record->moveLeft())->visible(fn (Page|RelationManager $livewire, ProductCategory $record) => $livewire->canEdit($record)),
                /* @phpstan-ignore-next-line */
                Action::make('↓')->button()->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->max('left')) ? $record : $record->moveRight())->visible(fn (Page|RelationManager $livewire, ProductCategory $record) => $livewire->canEdit($record)),
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
        /* @phpstan-ignore-next-line */
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

    protected function getParentResource(): string
    {
        return ProductCategoryResource::class;
    }

    protected function getViewPage(): string
    {
        return ProductCategoryResource\Pages\ViewProductCategory::class;
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return ProductCategoryResourceTranslationKey::class;
    }
}

<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Components\Actions\View\Tables\ViewAction;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Enums\Translation\ProductCategoryTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use Baum\Node;
use Filament\Resources\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class ProductCategoryChildrenRelationManager extends RelationManager
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
            ->appendActions([
                /* @phpstan-ignore-next-line */
                Action::make(' | ')->visible(fn (Page|RelationManager $livewire, ProductCategory $record) => $livewire->canEdit($record)),
                /* @phpstan-ignore-next-line */
                Action::make('↑')->button()->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->min('left')) ? $record : $record->moveLeft())->visible(fn (Page|RelationManager $livewire, ProductCategory $record) => $livewire->canEdit($record)),
                /* @phpstan-ignore-next-line */
                Action::make('↓')->button()->action(fn (ProductCategory $record): ProductCategory|Node => ($record->left === $record->parent?->children->max('left')) ? $record : $record->moveRight())->visible(fn (Page|RelationManager $livewire, ProductCategory $record) => $livewire->canEdit($record)),
            ])
            ->columns([
                TextColumn::makeTranslated(ProductCategoryTranslationKey::LEFT),
                TextColumn::makeTranslated(ProductCategoryTranslationKey::TITLE)->searchable(),
                TextColumn::makeTranslated(ProductCategoryTranslationKey::SLUG)->searchable(),
            ])
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

        return parent::canCreate() && $category->depth < ProductCategory::MAX_DEPTH;
    }

    /**
     * @param ProductCategory $record
     *
     * @return bool
     */
    public function canDelete(Model $record): bool
    {
        return parent::canDelete($record) && ProductCategoryResource::canDelete($record);
    }

    protected function canDeleteAny(): bool
    {
        return parent::canDeleteAny() && ProductCategoryResource::canDeleteAny();
    }
}

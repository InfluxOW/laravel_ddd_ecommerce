<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Admin\Components\Actions\Delete\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Edit\Tables\EditAction;
use App\Domains\Admin\Admin\Components\Actions\View\Tables\ViewAction;
use App\Domains\Admin\Admin\Traits\AppliesSearchToTableQuery;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Traits\HasNavigationSort;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationManager as BaseRelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Illuminate\Database\Eloquent\Model;

abstract class RelationManager extends BaseRelationManager
{
    use HasNavigationSort;
    use AppliesSearchToTableQuery;

    protected function getResourceTable(): Table
    {
        $table = Table::make();

        $table->actions([
            ViewAction::create(),
            EditAction::create(),
            DissociateAction::make(),
            DeleteAction::create(),
        ]);

        $table->bulkActions([
            DeleteBulkAction::make(),
            DissociateBulkAction::make(),
        ]);

        $table->headerActions([
            CreateAction::make(),
            AssociateAction::make(),
        ]);

        return static::table($table);
    }

    /*
     * Policies
     * */
    public function getOwnerRecord(): Model
    {
        return parent::getOwnerRecord();
    }

    protected function canView(Model $record): bool
    {
        return true;
    }

    protected function canAssociate(): bool
    {
        return false;
    }

    protected function canDissociate(Model $record): bool
    {
        return false;
    }

    protected function canDissociateAny(): bool
    {
        return false;
    }

    protected function canCreate(): bool
    {
        return $this->actionShouldBeDisplayed();
    }

    public function canEdit(Model $record): bool
    {
        return $this->actionShouldBeDisplayed();
    }

    public function canDelete(Model $record): bool
    {
        return $this->actionShouldBeDisplayed();
    }

    protected function canDeleteAny(): bool
    {
        return $this->actionShouldBeDisplayed();
    }

    private function actionShouldBeDisplayed(): bool
    {
        /** @var class-string<Page> $pageClass */
        $pageClass = $this->pageClass;
        /** @var string[] $pageClassParents */
        $pageClassParents = class_parents($pageClass);

        return empty($pageClassParents[ViewRecord::class]);
    }

    /*
     * Translated
     * */

    protected static function getRecordLabel(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminRelationPropertyTranslationKey::LABEL);

        return $translation;
    }

    protected static function getPluralRecordLabel(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminRelationPropertyTranslationKey::PLURAL_LABEL);

        return $translation;
    }

    public static function getTitle(): string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminRelationPropertyTranslationKey::TITLE);

        return $translation;
    }

    /*
     * Helpers
     * */

    protected function getModel(): string
    {
        return $this->getRelatedModel();
    }
}

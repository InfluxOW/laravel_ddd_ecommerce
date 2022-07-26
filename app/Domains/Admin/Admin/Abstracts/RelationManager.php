<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ViewAction;
use App\Domains\Admin\Admin\Traits\AppliesSearchToTableQuery;
use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\TranslatableAdminRelation;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationManager as BaseRelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;

abstract class RelationManager extends BaseRelationManager
{
    use TranslatableAdminRelation;
    use HasNavigationSort;
    use AppliesSearchToTableQuery;

    protected function getResourceTable(): Table
    {
        $table = Table::make();

        $table->actions([
            ViewAction::create(),
            EditAction::make(),
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

    protected function canEdit(Model $record): bool
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
     * Helpers
     * */

    protected function getModel(): string
    {
        return $this->getRelatedModel();
    }
}

<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ViewAction;
use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminRelation;
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
use Illuminate\Support\Facades\Request;

abstract class RelationManager extends BaseRelationManager
{
    use TranslatableAdminRelation;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;

    protected function getResourceTable(): Table
    {
        $table = Table::make();

        $table->actions([
            ViewAction::make(),
            EditAction::make(),
            DissociateAction::make(),
            DeleteAction::make(),
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

    protected function canView(Model $record): bool
    {
        return false;
    }

    protected function canAssociate(): bool
    {
        return false;
    }

    protected function canDissociate(Model $record): bool
    {
        return false;
    }

    protected function shouldBeDisplayed(): bool
    {
        $urls = [];

        foreach ($this->getViewableResourcesMap() as $resource => $page) {
            $pagename = str($page)->classBasename()->ucsplit()->map(fn (string $part): string => strtolower($part))->implode('-');
            $path = str($resource)->replace('App\\', '')->explode('\\')->map(fn (string $part): string => str($part)->ucsplit()->map(fn (string $part): string => strtolower($part))->implode('-'))->implode('.');

            $urls[] = $resource::getUrl('view', $this->ownerRecord->getKey());
            $urls[] = route('livewire.message', ["{$path}.pages.{$pagename}"]);
        }

        return collect($urls)->doesntContain(Request::url());
    }

    /**
     * @return array<class-string<Resource>, class-string<ViewRecord>>
     */
    abstract protected function getViewableResourcesMap(): array;
}

<?php

namespace App\Domains\Admin\Admin\Abstracts\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminRelation;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\HasManyRelationManager as BaseHasManyRelationManager;
use Illuminate\Support\Facades\Request;

abstract class HasManyRelationManager extends BaseHasManyRelationManager
{
    use TranslatableAdminRelation;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;

    protected function shouldBeDisplayed(): bool
    {
        $viewPagename = str($this->getViewPage())->classBasename()->ucsplit()->map(fn (string $part): string => strtolower($part))->implode('-');
        $path = str($this->getParentResource())->replace('App\\', '')->explode('\\')->map(fn (string $part): string => str($part)->ucsplit()->map(fn (string $part): string => strtolower($part))->implode('-'))->implode('.');

        return collect([
            $this->getParentResource()::getUrl('view', $this->ownerRecord->getKey()),
            route('livewire.message', ["{$path}.pages.{$viewPagename}"]),
        ])->doesntContain(Request::url());
    }

    /**
     * @return class-string<Resource>
     */
    abstract protected function getParentResource(): string;

    /**
     * @return class-string<ViewRecord>
     */
    abstract protected function getViewPage(): string;
}

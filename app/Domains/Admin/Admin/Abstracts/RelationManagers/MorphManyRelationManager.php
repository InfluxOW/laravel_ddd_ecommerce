<?php

namespace App\Domains\Admin\Admin\Abstracts\RelationManagers;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use Filament\Resources\RelationManagers\MorphManyRelationManager as BaseMorphManyRelationManager;

abstract class MorphManyRelationManager extends BaseMorphManyRelationManager
{
    use TranslatableAdminResource;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}

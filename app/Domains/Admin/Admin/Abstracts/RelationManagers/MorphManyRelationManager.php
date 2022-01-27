<?php

namespace App\Domains\Admin\Admin\Abstracts\RelationManagers;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminRelation;
use Filament\Resources\RelationManagers\MorphManyRelationManager as BaseMorphManyRelationManager;

abstract class MorphManyRelationManager extends BaseMorphManyRelationManager
{
    use TranslatableAdminRelation;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}

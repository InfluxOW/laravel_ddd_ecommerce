<?php

namespace App\Domains\Admin\Admin\Abstracts\RelationManagers;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminRelation;
use Filament\Resources\RelationManagers\HasManyRelationManager as BaseHasManyRelationManager;

abstract class HasManyRelationManager extends BaseHasManyRelationManager
{
    use TranslatableAdminRelation;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}

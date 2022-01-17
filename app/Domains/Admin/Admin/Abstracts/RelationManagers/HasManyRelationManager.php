<?php

namespace App\Domains\Admin\Admin\Abstracts\RelationManagers;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use Filament\Resources\RelationManagers\HasManyRelationManager as BaseHasManyRelationManager;

abstract class HasManyRelationManager extends BaseHasManyRelationManager
{
    use TranslatableAdminResource;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}

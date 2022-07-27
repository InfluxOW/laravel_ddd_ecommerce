<?php

namespace App\Domains\Admin\Enums\Translation\Components;

enum AdminActionTranslationKey: string
{
    case CREATE = 'create';
    case VIEW = 'view';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case UPDATE = 'update';
    case EXPORT = 'export';
    case BULK_EXPORT = 'bulk_export';
}

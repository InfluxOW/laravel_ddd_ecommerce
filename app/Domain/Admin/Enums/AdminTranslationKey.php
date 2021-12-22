<?php

namespace App\Domain\Admin\Enums;

enum AdminTranslationKey: string
{
    case TITLE = 'title';
    case LABEL = 'label';
    case PLURAL_LABEL = 'plural_label';
    case NAVIGATION_LABEL = 'navigation_label';
    case NAVIGATION_GROUP = 'navigation_group';
}

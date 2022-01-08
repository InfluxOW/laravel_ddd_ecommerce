<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;

trait TranslatableAdminWidget
{
    use TranslatableAdmin;

    protected function getHeading(): string
    {
        return static::translateComponentProperty(AdminWidgetPropertyTranslationKey::HEADING);
    }
}

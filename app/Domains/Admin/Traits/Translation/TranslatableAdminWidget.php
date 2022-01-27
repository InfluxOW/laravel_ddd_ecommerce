<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;

trait TranslatableAdminWidget
{
    use TranslatableAdmin;

    protected function getHeading(): ?string
    {
        /** @var string $translation */
        $translation = static::translateComponentProperty(AdminWidgetPropertyTranslationKey::HEADING);

        return $translation;
    }
}

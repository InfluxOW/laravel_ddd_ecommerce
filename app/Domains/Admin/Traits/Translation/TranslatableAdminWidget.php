<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;

trait TranslatableAdminWidget
{
    protected function getHeading(): ?string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminWidgetPropertyTranslationKey::HEADING);

        return $translation;
    }
}

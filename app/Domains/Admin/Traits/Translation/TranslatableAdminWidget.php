<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Admin\Traits\Translation\Internal\TranslatableAdmin;

trait TranslatableAdminWidget
{
    use TranslatableAdmin;

    protected function getHeading(): ?string
    {
        /** @var string $translation */
        $translation = self::translateComponentProperty(AdminWidgetPropertyTranslationKey::HEADING);

        return $translation;
    }
}

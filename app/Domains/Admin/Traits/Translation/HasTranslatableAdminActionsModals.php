<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Traits\Translation\Internal\TranslatableAdmin;
use Filament\Support\Actions\Concerns\CanOpenModal;

trait HasTranslatableAdminActionsModals
{
    use TranslatableAdmin;

    /**
     * @param object[] $schema
     *
     * @return array
     */
    protected static function setTranslatableModals(array $schema): array
    {
        return collect($schema)
            ->map(fn (object $item): object => static::setTranslatableModal($item))
            ->toArray();
    }

    protected static function setTranslatableModal(object $item): object
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[CanOpenModal::class])) {
            /** @phpstan-ignore-next-line */
            $item
                ->modalHeading(self::translateAdminEnum(AdminModalTranslationKey::HEADING, allowClosures: true))
                ->modalSubheading(self::translateAdminEnum(AdminModalTranslationKey::SUBHEADING, allowClosures: true))
                ->modalButton(self::translateAdminEnum(AdminModalTranslationKey::BUTTON, allowClosures: true));
        }

        return $item;
    }
}

<?php

namespace App\Domains\Admin\Traits\Translation;

use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use Filament\Support\Actions\Concerns\CanOpenModal;
use UnitEnum;

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
                ->modalHeading(static::translateAdminEnum(AdminModalTranslationKey::HEADING, allowClosures: true))
                ->modalSubheading(static::translateAdminEnum(AdminModalTranslationKey::SUBHEADING, allowClosures: true))
                ->modalButton(static::translateAdminEnum(AdminModalTranslationKey::BUTTON, allowClosures: true));
        }

        return $item;
    }

    /**
     * @phpstan-ignore-next-line
     *
     * @return string<UnitEnum>
     */
    abstract protected static function getTranslationKeyClass(): string;
}

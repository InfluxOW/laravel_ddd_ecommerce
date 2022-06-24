<?php

namespace App\Domains\Admin\Traits\Translation;

use UnitEnum;

trait HasTranslatableAdminLabels
{
    use TranslatableAdmin;

    /**
     * @param object[] $schema
     *
     * @return array
     */
    protected static function setTranslatableLabels(array $schema): array
    {
        return collect($schema)
            ->map(fn (object $item): object => static::setTranslatableLabel($item))
            ->toArray();
    }

    protected static function setTranslatableLabel(object $item): object
    {
        if (method_exists($item, 'getName') && method_exists($item, 'label')) {
            $formTranslationKeyEnum = static::getTranslationKeyClass()::tryFrom($item->getName());
            if (isset($formTranslationKeyEnum)) {
                $item->label(static::translateEnum($formTranslationKeyEnum, allowClosures: true));
            }
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

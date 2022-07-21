<?php

namespace App\Domains\Admin\Traits\Translation;

use BackedEnum;

trait HasTranslatableAdminLabels
{
    use TranslatableAdmin;

    /**
     * @param object[]                      $schema
     * @param class-string<BackedEnum>|null $translationKeyClass
     *
     * @return array
     */
    protected static function setTranslatableLabels(array $schema, ?string $translationKeyClass = null): array
    {
        return collect($schema)
            ->map(fn (object $item): object => static::setTranslatableLabel($item, $translationKeyClass))
            ->toArray();
    }

    /**
     * @param class-string<BackedEnum>|null $translationKeyClass
     */
    protected static function setTranslatableLabel(object $item, ?string $translationKeyClass = null): object
    {
        if (method_exists($item, 'getName') && method_exists($item, 'label')) {
            $itemName = $item->getName();
            $formTranslationKeyEnum = ($translationKeyClass ?? static::getTranslationKeyClass())::tryFrom($itemName);
            if (isset($formTranslationKeyEnum)) {
                $item->label(static::translateEnum($formTranslationKeyEnum, allowClosures: true));
            }
        }

        return $item;
    }

    /**
     * @return class-string<BackedEnum>
     */
    abstract protected static function getTranslationKeyClass(): string;
}

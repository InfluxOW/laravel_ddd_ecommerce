<?php

namespace App\Domains\Admin\Traits\Translation;

trait HasTranslatableAdminLabels
{
    use TranslatableAdmin;

    /**
     * @param object[] $schema
     * @return array
     */
    protected static function setTranslatableLabels(array $schema): array
    {
        return collect($schema)
            ->map(function (object $item): object {
                if (method_exists($item, 'getName') && method_exists($item, 'label')) {
                    $formTranslationKeyEnum = static::getTranslationKeyClass()::tryFrom($item->getName());
                    if (isset($formTranslationKeyEnum)) {
                        $item->label(static::translateEnum($formTranslationKeyEnum));
                    }
                }

                return $item;
            })
            ->toArray();
    }

    /**
     * @return string<UnitEnum>
     */
    abstract protected static function getTranslationKeyClass(): string;
}

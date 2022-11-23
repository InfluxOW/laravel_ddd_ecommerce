<?php

namespace App\Domains\Admin\Services;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Common\Enums\Lang\TranslationFilename;
use App\Domains\Common\Exceptions\NotSupportedClassException;
use App\Domains\Common\Utils\AppUtils;
use App\Domains\Common\Utils\ClassUtils;
use App\Domains\Common\Utils\LangUtils;
use BackedEnum;
use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
use Filament\Pages\SettingsPage;
use Filament\Support\Actions\Action;
use Filament\Support\Actions\Concerns\CanOpenModal;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Widgets\Widget;
use UnitEnum;

final class Translator
{
    private const TRANSLATABLE_COMPONENTS_CLASSES = [Action::class, Column::class, Field::class, Component::class, BaseFilter::class];

    private const TRANSLATABLE_BASE_ENTITIES_CLASSES = [SettingsPage::class, RelationManager::class, Widget::class, SimpleResource::class];

    /**
     * @param class-string $class
     *
     * @throws NotSupportedClassException
     */
    public function makeTranslated(string $class, BackedEnum $value): object
    {
        ClassUtils::blockNotSupportedClasses($class, self::TRANSLATABLE_COMPONENTS_CLASSES);

        /** @var array<string, class-string> $parents */
        $parents = class_parents($class);
        $uses = class_uses_recursive($class);

        $component = isset($parents[Action::class]) || isset($parents[Column::class]) || isset($parents[Field::class]) ? $class::make($value->value)->label(LangUtils::translateEnum($value, allowClosures: true)) : $class::make(LangUtils::translateEnum($value, allowClosures: true));

        if (isset($uses[CanOpenModal::class])) {
            /** @var Action $component */
            $component
                ->modalHeading($this->translateAdminEnum($class, AdminModalTranslationKey::HEADING, allowClosures: true))
                ->modalSubheading($this->translateAdminEnum($class, AdminModalTranslationKey::SUBHEADING, allowClosures: true))
                ->modalButton($this->translateAdminEnum($class, AdminModalTranslationKey::BUTTON, allowClosures: true));
        }

        return $component;
    }

    /**
     * @param class-string $class
     *
     * @throws NotSupportedClassException
     */
    public function translateComponentProperty(string $class, AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey|AdminWidgetPropertyTranslationKey $enum, bool $allowClosures): string|Closure
    {
        ClassUtils::blockNotSupportedClasses($class, self::TRANSLATABLE_BASE_ENTITIES_CLASSES);

        return $this->translateAdminEnum($class, $enum, $allowClosures);
    }

    /**
     * @param class-string $class
     */
    private function translateAdminEnum(string $class, UnitEnum $enum, bool $allowClosures): string|Closure
    {
        return LangUtils::translateValue(AppUtils::guessServiceProviderNamespace($class), TranslationFilename::ADMIN, $enum->name, $class, $allowClosures);
    }
}

<?php

namespace App\Domains\Admin\Providers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Common\Enums\Lang\TranslationFilename;
use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Domains\Common\Utils\AppUtils;
use App\Domains\Common\Utils\LangUtils;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use BackedEnum;
use Closure;
use Filament\Forms\Components\Component as FilamentFormComponent;
use Filament\Forms\Components\Field;
use Filament\Pages\SettingsPage;
use Filament\Support\Actions\Action;
use Filament\Support\Actions\Concerns\CanOpenModal;
use Filament\Support\Components\ViewComponent;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Widgets\Widget;
use Livewire\Component as LivewireComponent;
use UnitEnum;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::ADMIN;

    protected function afterBooting(): void
    {
        $this->registerMacroses();
    }

    private function registerMacroses(): void
    {
        $this->registerTranslateAdminEnumMacros();
        $this->registerTranslateComponentPropertyMacros();
        $this->registerMakeTranslatedMacros();
    }

    private function registerMakeTranslatedMacros(): void
    {
        $blockNotSupportedClasses = fn (string $class, array $supportedClasses) => self::blockNotSupportedClasses($class, $supportedClasses);
        $makeComponent = fn (UnitEnum $value, string $class): object => self::makeComponent($value, $class);
        $translateModals = fn (object $component, string $class): object => self::translateModals($component, $class);

        ViewComponent::macro('makeTranslated', function (BackedEnum $value) use ($blockNotSupportedClasses, $makeComponent, $translateModals): static {
            $class = static::class;

            $blockNotSupportedClasses($class, [Action::class, Column::class, Field::class, FilamentFormComponent::class, BaseFilter::class]);

            /** @var static $component */
            $component = $makeComponent($value, $class);

            $translateModals($component, $class);

            return $component;
        });
    }

    private function registerTranslateComponentPropertyMacros(): void
    {
        $blockNotSupportedClasses = fn (string $class, array $supportedClasses) => self::blockNotSupportedClasses($class, $supportedClasses);

        $translateComponentProperty = function (AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey|AdminWidgetPropertyTranslationKey $enum, bool $allowClosures = false) use ($blockNotSupportedClasses): string|Closure {
            $class = static::class;

            $blockNotSupportedClasses($class, [
                SettingsPage::class,
                RelationManager::class,
                Widget::class,
                SimpleResource::class,
                Action::class,
            ]);

            return $class::translateAdminEnum($enum, $allowClosures);
        };

        LivewireComponent::macro('translateComponentProperty', $translateComponentProperty);
        ViewComponent::macro('translateComponentProperty', $translateComponentProperty);
        SimpleResource::macro('translateComponentProperty', $translateComponentProperty);
    }

    private function registerTranslateAdminEnumMacros(): void
    {
        $blockNotSupportedClasses = fn (string $class, array $supportedClasses) => self::blockNotSupportedClasses($class, $supportedClasses);

        $translateAdminEnum = function (UnitEnum $enum, bool $allowClosures = false) use ($blockNotSupportedClasses): string|Closure {
            $class = static::class;

            $blockNotSupportedClasses($class, [
                SettingsPage::class,
                RelationManager::class,
                Widget::class,
                SimpleResource::class,
                Action::class,
            ]);

            return LangUtils::translateValue(AppUtils::guessServiceProviderNamespace($class), TranslationFilename::ADMIN, $enum->name, $class, $allowClosures);
        };

        LivewireComponent::macro('translateAdminEnum', $translateAdminEnum);
        ViewComponent::macro('translateAdminEnum', $translateAdminEnum);
        SimpleResource::macro('translateAdminEnum', $translateAdminEnum);
    }

    private static function makeComponent(UnitEnum $value, string $class): object
    {
        /** @var array<string, class-string> $parents */
        $parents = class_parents($class);

        if (isset($parents[Action::class]) || isset($parents[Column::class]) || isset($parents[Field::class])) {
            /** @phpstan-ignore-next-line */
            return $class::make($value->value)->label(LangUtils::translateEnum($value, allowClosures: true));
        }

        return $class::make(LangUtils::translateEnum($value, allowClosures: true));
    }

    private static function translateModals(object $component, string $class): object
    {
        $uses = class_uses_recursive($class);

        if (isset($uses[CanOpenModal::class])) {
            /** @var Action $component */
            $component
                ->modalHeading($class::translateAdminEnum(AdminModalTranslationKey::HEADING, allowClosures: true))
                ->modalSubheading($class::translateAdminEnum(AdminModalTranslationKey::SUBHEADING, allowClosures: true))
                ->modalButton($class::translateAdminEnum(AdminModalTranslationKey::BUTTON, allowClosures: true));
        }

        return $component;
    }
}

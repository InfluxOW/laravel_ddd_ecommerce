<?php

namespace App\Domains\Admin\Providers;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Generic\Enums\Lang\TranslationFilename;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Generic\Utils\AppUtils;
use App\Domains\Generic\Utils\LangUtils;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use BackedEnum;
use Closure;
use Filament\Pages\SettingsPage;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Widgets\Widget;
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
        /** @phpstan-ignore-next-line */
        $makeTranslated = fn (BackedEnum $value): static => static::make($value->value)->label(LangUtils::translateEnum($value, allowClosures: true));

        /** @phpstan-ignore-next-line */
        Action::macro('makeTranslated', fn (BackedEnum $value): static => static::make($value->value)
            ->label(LangUtils::translateEnum($value, allowClosures: true))
            /** @phpstan-ignore-next-line */
            ->modalHeading(self::translateAdminEnum(AdminModalTranslationKey::HEADING, allowClosures: true))
            /** @phpstan-ignore-next-line */
            ->modalSubheading(self::translateAdminEnum(AdminModalTranslationKey::SUBHEADING, allowClosures: true))
            /** @phpstan-ignore-next-line */
            ->modalButton(self::translateAdminEnum(AdminModalTranslationKey::BUTTON, allowClosures: true)));
        BaseFilter::macro('makeTranslated', $makeTranslated);
        Column::macro('makeTranslated', $makeTranslated);
    }

    private function registerTranslateComponentPropertyMacros(): void
    {
        /** @phpstan-ignore-next-line */
        $translateComponentProperty = fn (AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey|AdminWidgetPropertyTranslationKey $enum, bool $allowClosures = false): string|Closure => static::translateAdminEnum($enum, $allowClosures);

        SettingsPage::macro('translateComponentProperty', $translateComponentProperty);
        RelationManager::macro('translateComponentProperty', $translateComponentProperty);
        Widget::macro('translateComponentProperty', $translateComponentProperty);
        SimpleResource::macro('translateComponentProperty', $translateComponentProperty);
        Action::macro('translateComponentProperty', $translateComponentProperty);
    }

    private function registerTranslateAdminEnumMacros(): void
    {
        $translateAdminEnum = fn (UnitEnum $enum, bool $allowClosures = false): string|Closure => LangUtils::translateValue(AppUtils::guessServiceProviderNamespace(static::class), TranslationFilename::ADMIN, $enum->name, static::class, $allowClosures);

        SettingsPage::macro('translateAdminEnum', $translateAdminEnum);
        RelationManager::macro('translateAdminEnum', $translateAdminEnum);
        Widget::macro('translateAdminEnum', $translateAdminEnum);
        SimpleResource::macro('translateAdminEnum', $translateAdminEnum);
        Action::macro('translateAdminEnum', $translateAdminEnum);
    }
}

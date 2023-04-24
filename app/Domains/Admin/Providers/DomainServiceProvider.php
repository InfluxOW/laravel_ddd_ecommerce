<?php

namespace App\Domains\Admin\Providers;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Admin\Models\Admin;
use App\Domains\Admin\Services\Translator;
use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use BackedEnum;
use Closure;
use Filament\Support\Components\ViewComponent;
use Livewire\Component as LivewireComponent;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::ADMIN;

    protected array $morphMap = [
        'admin' => Admin::class,
    ];

    protected function afterBooting(): void
    {
        $this->registerMacroses();
    }

    private function registerMacroses(): void
    {
        $translator = app(Translator::class);

        $makeTranslated = function (BackedEnum $value) use ($translator): static {
            /** @var static $component */
            $component = $translator->makeTranslated(self::class, $value);

            return $component;
        };
        $translateComponentProperty = fn (AdminResourcePropertyTranslationKey|AdminRelationPropertyTranslationKey|AdminPagePropertyTranslationKey|AdminWidgetPropertyTranslationKey $enum, bool $allowClosures = false): string|Closure => $translator->translateComponentProperty(self::class, $enum, $allowClosures);

        ViewComponent::macro('makeTranslated', $makeTranslated);

        ViewComponent::macro('translateComponentProperty', $translateComponentProperty);
        LivewireComponent::macro('translateComponentProperty', $translateComponentProperty);
        SimpleResource::macro('translateComponentProperty', $translateComponentProperty);
    }
}

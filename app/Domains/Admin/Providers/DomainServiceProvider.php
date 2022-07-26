<?php

namespace App\Domains\Admin\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Generic\Utils\LangUtils;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use BackedEnum;
use Filament\Support\Actions\BaseAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::ADMIN;

    protected function afterBooting(): void
    {
        $this->registerMacroses();
    }

    private function registerMacroses(): void
    {
        /** @phpstan-ignore-next-line */
        $makeTranslated = static fn (BackedEnum $value): static => static::make($value->value)->label(LangUtils::translateEnum($value, allowClosures: true));

        Column::macro('makeTranslated', $makeTranslated);
        BaseAction::macro('makeTranslated', $makeTranslated);
        BaseFilter::macro('makeTranslated', $makeTranslated);
    }
}

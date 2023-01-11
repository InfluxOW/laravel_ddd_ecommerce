<?php

namespace App\Domains\Users\Admin\Components\Widgets;

use App\Domains\Admin\Traits\Translation\TranslatableAdminWidget;
use App\Domains\Common\Utils\LangUtils;
use App\Domains\Users\Enums\Translation\UserDatasetTranslationKey;
use App\Domains\Users\Models\User;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class CustomersChartWidget extends LineChartWidget
{
    use TranslatableAdminWidget;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(User::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => LangUtils::translateEnum(UserDatasetTranslationKey::CUSTOMERS),
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}

<?php

namespace App\Domains\Users\Jobs\Export;

use App\Domains\Common\Classes\Excel\ExportColumn;
use App\Domains\Common\Jobs\ExportJob;
use App\Domains\Users\Enums\Translation\UserTranslationKey;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class UsersExportJob extends ExportJob
{
    protected function getBaseQuery(): Builder
    {
        return User::query()
            ->with([
                'loginHistory' => fn (MorphMany $query): MorphMany => $query->select(['id', 'login_historyable_type', 'login_historyable_id', 'created_at'])->orderByDesc('id')->limit(1),
            ])
            ->select(['id', 'name', 'email', 'phone', 'email_verified_at', 'created_at', 'updated_at']);
    }

    protected function rows(): Collection
    {
        return collect([
            ExportColumn::create(UserTranslationKey::ID, NumberFormat::FORMAT_NUMBER),
            ExportColumn::create(UserTranslationKey::NAME),
            ExportColumn::create(UserTranslationKey::EMAIL),
            ExportColumn::create(UserTranslationKey::HAS_VERIFIED_EMAIL),
            ExportColumn::create(UserTranslationKey::PHONE),
            ExportColumn::create(UserTranslationKey::LAST_LOGGED_IN_AT, NumberFormat::FORMAT_DATE_DATETIME),
            ExportColumn::create(UserTranslationKey::CREATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
            ExportColumn::create(UserTranslationKey::UPDATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
        ]);
    }
}

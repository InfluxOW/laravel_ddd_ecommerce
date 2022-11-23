<?php

namespace App\Domains\Feedback\Jobs\Export;

use App\Domains\Common\Classes\Excel\ExportColumn;
use App\Domains\Common\Jobs\ExportJob;
use App\Domains\Feedback\Enums\Translation\FeedbackTranslationKey;
use App\Domains\Feedback\Models\Feedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class FeedbackExportJob extends ExportJob
{
    protected function getBaseQuery(): Builder
    {
        return Feedback::query()->select(['id', 'username', 'email', 'phone', 'text', 'created_at']);
    }

    protected function rows(): Collection
    {
        return collect([
            ExportColumn::create(FeedbackTranslationKey::ID, NumberFormat::FORMAT_NUMBER),
            ExportColumn::create(FeedbackTranslationKey::USERNAME),
            ExportColumn::create(FeedbackTranslationKey::EMAIL),
            ExportColumn::create(FeedbackTranslationKey::PHONE),
            ExportColumn::create(FeedbackTranslationKey::TEXT),
            ExportColumn::create(FeedbackTranslationKey::CREATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
        ]);
    }
}

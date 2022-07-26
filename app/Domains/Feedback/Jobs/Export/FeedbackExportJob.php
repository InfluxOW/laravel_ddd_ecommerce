<?php

namespace App\Domains\Feedback\Jobs\Export;

use App\Domains\Feedback\Enums\Translation\FeedbackTranslationKey;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Generic\Classes\Excel\ExportColumn;
use App\Domains\Generic\Jobs\ExportJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class FeedbackExportJob extends ExportJob
{
    public function query(): Builder
    {
        return Feedback::query()
            ->select(['id', 'username', 'email', 'phone', 'text', 'created_at'])
            /** @phpstan-ignore-next-line */
            ->when(isset($this->recordsIds), fn (Builder $query): Builder => $query->whereIntegerInRaw('id', $this->recordsIds))
            ->orderBy('id');
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

<?php

namespace App\Domains\News\Jobs\Export;

use App\Domains\Common\Classes\Excel\ExportColumn;
use App\Domains\Common\Jobs\ExportJob;
use App\Domains\News\Enums\Translation\ArticleTranslationKey;
use App\Domains\News\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class NewsExportJob extends ExportJob
{
    protected function getBaseQuery(): Builder
    {
        return Article::query()->select(['id', 'title', 'slug', 'description', 'body', 'published_at', 'created_at', 'updated_at']);
    }

    protected function rows(): Collection
    {
        return collect([
            ExportColumn::create(ArticleTranslationKey::ID, NumberFormat::FORMAT_NUMBER),
            ExportColumn::create(ArticleTranslationKey::TITLE),
            ExportColumn::create(ArticleTranslationKey::SLUG),
            ExportColumn::create(ArticleTranslationKey::DESCRIPTION),
            ExportColumn::create(ArticleTranslationKey::BODY),
            ExportColumn::create(ArticleTranslationKey::PUBLISHED_AT, NumberFormat::FORMAT_DATE_DATETIME),
            ExportColumn::create(ArticleTranslationKey::CREATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
            ExportColumn::create(ArticleTranslationKey::UPDATED_AT, NumberFormat::FORMAT_DATE_DATETIME),
        ]);
    }
}

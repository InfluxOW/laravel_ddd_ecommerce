<?php

namespace App\Domains\News\Tests\Feature\Admin\Export;

use App\Domains\Admin\Tests\Feature\Admin\ExportTest;
use App\Domains\News\Admin\Resources\ArticleResource\Pages\ListNews;
use App\Domains\News\Database\Seeders\ArticleSeeder;

final class NewsExportTest extends ExportTest
{
    protected string $listRecords = ListNews::class;

    protected function setUpOnce(): void
    {
        $this->seed([
            ArticleSeeder::class,
        ]);
    }
}

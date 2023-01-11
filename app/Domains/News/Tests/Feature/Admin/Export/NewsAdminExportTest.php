<?php

namespace App\Domains\News\Tests\Feature\Admin\Export;

use App\Domains\Admin\Tests\AdminExportTest;
use App\Domains\News\Admin\Resources\ArticleResource\Pages\ListNews;
use App\Domains\News\Database\Seeders\ArticleSeeder;

final class NewsAdminExportTest extends AdminExportTest
{
    protected static array $seeders = [
        ArticleSeeder::class,
    ];

    protected string $listRecords = ListNews::class;
}

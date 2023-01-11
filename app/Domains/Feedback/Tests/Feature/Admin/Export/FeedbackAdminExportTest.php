<?php

namespace App\Domains\Feedback\Tests\Feature\Admin\Export;

use App\Domains\Admin\Tests\AdminExportTest;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ListFeedback;
use App\Domains\Feedback\Database\Seeders\FeedbackSeeder;
use App\Domains\Users\Database\Seeders\UserSeeder;

final class FeedbackAdminExportTest extends AdminExportTest
{
    protected static array $seeders = [
        UserSeeder::class,
        FeedbackSeeder::class,
    ];

    protected string $listRecords = ListFeedback::class;
}

<?php

namespace App\Domains\Feedback\Tests\Admin;

use App\Domains\Admin\Tests\Admin\ExportTest;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ListFeedback;
use App\Domains\Feedback\Database\Seeders\FeedbackSeeder;
use App\Domains\Users\Database\Seeders\UserSeeder;

final class FeedbackExportTest extends ExportTest
{
    protected string $listRecords = ListFeedback::class;

    protected function setUpOnce(): void
    {
        $this->seed([
            UserSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}

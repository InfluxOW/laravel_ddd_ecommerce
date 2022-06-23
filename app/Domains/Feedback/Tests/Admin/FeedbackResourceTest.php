<?php

namespace App\Domains\Feedback\Tests\Admin;

use App\Application\Tests\Admin\AdminCrudTestCase;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ListFeedback;
use App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages\ViewFeedback;
use App\Domains\Feedback\Database\Seeders\FeedbackSeeder;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Model;

final class FeedbackResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListFeedback::class;

    protected ?string $viewRecord = ViewFeedback::class;

    protected array $seeders = [
        UserSeeder::class,
        FeedbackSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return Feedback::query()->first();
    }
}

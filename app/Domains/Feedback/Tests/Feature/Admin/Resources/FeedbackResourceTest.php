<?php

namespace App\Domains\Feedback\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Domains\Feedback\Admin\Resources\FeedbackResource;
use App\Domains\Feedback\Database\Seeders\FeedbackSeeder;
use App\Domains\Users\Database\Seeders\UserSeeder;

final class FeedbackResourceTest extends AdminCrudTestCase
{
    protected static string $resource = FeedbackResource::class;

    protected array $seeders = [
        UserSeeder::class,
        FeedbackSeeder::class,
    ];
}

<?php

namespace App\Domains\News\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Domains\News\Admin\Resources\ArticleResource;
use App\Domains\News\Database\Seeders\ArticleSeeder;

final class ArticleResourceTest extends AdminCrudTestCase
{
    protected static string $resource = ArticleResource::class;

    protected static array $seeders = [
        ArticleSeeder::class,
    ];
}

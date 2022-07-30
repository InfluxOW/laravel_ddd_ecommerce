<?php

namespace App\Domains\News\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Domains\News\Admin\Resources\ArticleResource\Pages\CreateArticle;
use App\Domains\News\Admin\Resources\ArticleResource\Pages\EditArticle;
use App\Domains\News\Admin\Resources\ArticleResource\Pages\ListNews;
use App\Domains\News\Admin\Resources\ArticleResource\Pages\ViewArticle;
use App\Domains\News\Database\Seeders\ArticleSeeder;
use App\Domains\News\Models\Article;
use Illuminate\Database\Eloquent\Model;

final class ArticleResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListNews::class;

    protected ?string $viewRecord = ViewArticle::class;

    protected ?string $editRecord = EditArticle::class;

    protected ?string $createRecord = CreateArticle::class;

    protected array $seeders = [
        ArticleSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return Article::query()->first();
    }
}

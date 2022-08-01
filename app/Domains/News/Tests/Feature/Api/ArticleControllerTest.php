<?php

namespace App\Domains\News\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Domains\News\Database\Seeders\ArticleSeeder;
use App\Domains\News\Models\Article;
use Carbon\Carbon;

final class ArticleControllerTest extends TestCase
{
    private static Article $article;

    protected function setUpOnce(): void
    {
        $this->seed([
            ArticleSeeder::class,
        ]);

        /** @var Article $article */
        $article = Article::query()->first();

        self::$article = $article;
    }

    /** @test */
    public function a_user_can_view_news_list(): void
    {
        $this->get(route('news.index'))->assertOk();
    }

    /** @test */
    public function a_user_not_view_published_article(): void
    {
        $article = self::$article;
        $article->published_at = Carbon::now()->subYear();
        $article->save();

        $this->get(route('news.show', $article))->assertOk();
    }

    /** @test */
    public function a_user_can_not_view_non_published_article(): void
    {
        $article = self::$article;
        $article->published_at = Carbon::now()->addYear();
        $article->save();

        $this->get(route('news.show', $article))->assertNotFound();
    }
}

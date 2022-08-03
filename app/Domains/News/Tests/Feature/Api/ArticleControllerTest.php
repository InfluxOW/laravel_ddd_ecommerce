<?php

namespace App\Domains\News\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\News\Database\Seeders\ArticleSeeder;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use App\Domains\News\Enums\Query\Sort\ArticleAllowedSort;
use App\Domains\News\Models\Article;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    /**
     * @test
     * @unstable
     */
    public function a_user_can_search_news(): void
    {
        $this->refreshModelIndex(Article::class);

        $queries = [
            self::$article->title,
            Str::words(self::$article->title, 2, ''),
            Str::words(self::$article->description, 2, ''),
            Str::words(self::$article->body, 2, ''),
        ];

        $newsCount = Article::query()->published()->count();
        foreach ($queries as $query) {
            $response = $this->get(
                route('news.index', [QueryKey::FILTER->value => [ArticleAllowedFilter::SEARCH->name => $query], QueryKey::PER_PAGE->value => $newsCount])
            )->assertOk();

            $this->assertContains(self::$article->slug, $this->getResponseData($response)->pluck('slug'));
            $this->assertContains(ArticleAllowedFilter::SEARCH->name, $this->getResponseAppliedFilters($response)->pluck('query'));
        }
    }

    /** @test */
    public function a_user_can_filter_news_by_published_date(): void
    {
        $publishedAt = Carbon::now()->subMonths(6);

        self::$article->published_at = $publishedAt;
        self::$article->save();

        $monthBefore = $publishedAt->clone()->subMonths(3)->defaultFormat();
        $monthAfter = $publishedAt->clone()->addMonths(3)->defaultFormat();

        $queries = [
            [$monthBefore, $monthAfter],
            [$monthAfter, $monthBefore],
            [null, $monthAfter],
            [$monthBefore, null],
            [null, null],
        ];

        foreach ($queries as [$minDate, $maxDate]) {
            $response = $this->get(
                route('news.index', [QueryKey::FILTER->value => [ArticleAllowedFilter::PUBLISHED_BETWEEN->name => "{$minDate},{$maxDate}"]])
            )->assertOk();

            $news = $this->getResponseData($response);
            $this->assertNotEmpty($news);

            if (isset($minDate, $maxDate) && $maxDate < $minDate) {
                [$minDate, $maxDate] = [$maxDate, $minDate];
            }

            if (isset($minDate)) {
                $this->assertTrue($news->every(fn (array $article): bool => $article['published_at'] >= $minDate));
            }

            if (isset($maxDate)) {
                $this->assertTrue($news->every(fn (array $article): bool => $article['published_at'] <= $maxDate));
            }

            $isPublishedBetweenFilter = fn (array $filter): bool => $filter['query'] === ArticleAllowedFilter::PUBLISHED_BETWEEN->name;
            $publishedBetweenAppliedFilter = $this->getResponseAppliedFilters($response)->filter($isPublishedBetweenFilter)->first();
            $publishedBetweenAllowedFilter = $this->getResponseAllowedFilters($response)->filter($isPublishedBetweenFilter)->first();

            $lowestAvailablePublishedAt = $publishedBetweenAllowedFilter['min'];
            $highestAvailablePublishedAt = $publishedBetweenAllowedFilter['max'];

            $this->assertEquals(isset($minDate) ? max($minDate, $lowestAvailablePublishedAt) : $lowestAvailablePublishedAt, $publishedBetweenAppliedFilter['min']);
            $this->assertEquals(isset($maxDate) ? min($maxDate, $highestAvailablePublishedAt) : $highestAvailablePublishedAt, $publishedBetweenAppliedFilter['max']);
        }
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

    /** @test */
    public function a_user_can_sort_news_by_title_a_to_z(): void
    {
        $this->checkNewsSort(ArticleAllowedSort::TITLE);
    }

    /** @test */
    public function a_user_can_sort_news_by_title_z_to_a(): void
    {
        $this->checkNewsSort(ArticleAllowedSort::TITLE_DESC);
    }

    /** @test */
    public function a_user_can_sort_news_by_lately_published_first(): void
    {
        $this->checkNewsSort(ArticleAllowedSort::PUBLISHED_AT);
    }

    /** @test */
    public function a_user_can_sort_news_by_recently_published_first(): void
    {
        $this->checkNewsSort(ArticleAllowedSort::PUBLISHED_AT_DESC);
    }

    private function checkNewsSort(ArticleAllowedSort $sort): void
    {
        $products = $this->getNewsSortedBy($sort);

        $this->assertEquals(
            $products->pluck($sort->getDatabaseColumn()),
            $products->sortBy(...$this->getSortParametersByType($sort))->pluck($sort->getDatabaseColumn())
        );
    }

    private function getNewsSortedBy(ArticleAllowedSort $sort): Collection
    {
        $response = $this->get(
            route('news.index', [QueryKey::SORT->value => $sort->name, QueryKey::PER_PAGE->value => Article::query()->published()->count()])
        )->assertOk();

        $news = $this->getResponseData($response);
        $appliedSort = $this->getResponseAppliedSort($response);

        $this->assertEquals($appliedSort['query'], $sort->name);

        return $news;
    }

    private function getSortParametersByType(ArticleAllowedSort $sort): array
    {
        $titleSort = static fn (array $article): string => $article['title'];
        $publishedAtSort = static fn (array $article): int => (int) Carbon::createFromDefaultFormat($article['published_at'])->timestamp;

        return match ($sort) {
            ArticleAllowedSort::TITLE => [$titleSort, SORT_STRING, false],
            ArticleAllowedSort::TITLE_DESC => [$titleSort, SORT_STRING, true],
            ArticleAllowedSort::PUBLISHED_AT => [$publishedAtSort, SORT_NUMERIC, false],
            ArticleAllowedSort::PUBLISHED_AT_DESC => [$publishedAtSort, SORT_NUMERIC, true],
            ArticleAllowedSort::DEFAULT => throw new Exception('To be implemented'),
        };
    }
}

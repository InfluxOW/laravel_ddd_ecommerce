<?php

namespace App\Domains\Catalog\Database\Factories;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Infrastructure\Abstracts\Database\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class ProductFactory extends Factory
{
    protected $model = Product::class;

    public Collection $categoriesIds;

    protected function setUp(): void
    {
        $this->categoriesIds = Cache::rememberInArray(
            json_encode([self::class => 'categories_ids'], JSON_THROW_ON_ERROR),
            static fn (): Collection => self::getProductCategoriesIds()
        );
    }

    public function definition(): array
    {
        /** @var string $title */
        $title = $this->faker->unique()->words(3, true);

        return self::addTimestamps([
            'title' => $title,
            'slug' => Str::of($title)->slug(),
            'description' => $this->faker->words(50, true),
            'created_at' => $this->faker->dateTimeBetween('-2 years'),
        ]);
    }

    public function configure(): self
    {
        return $this
            ->afterCreating(function (Product $product): void {
                $product->categories()->sync(self::getRandomCategoriesIds($this->categoriesIds));
            });
    }

    public static function getRandomCategoriesIds(Collection $categories): Collection|ProductCategory
    {
        return $categories->random(app()->runningUnitTests() ? 1 : random_int(1, 5));
    }

    public static function getProductCategoriesIds(): Collection
    {
        return ProductCategory::query()->where('depth', '>=', ProductCategory::MAX_DEPTH - 1)->hasLimitedDepth()->pluck('id');
    }
}

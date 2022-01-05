<?php

namespace App\Domain\Catalog\Models;

use App\Domain\Catalog\Database\Factories\ProductCategoryFactory;
use Baum\NestedSet\Node;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\ProductCategory
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $is_visible
 * @property int|null $parent_id
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null $children_count
 * @property-read int $overall_products_count
 * @property-read string $path
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $immediateDescendants
 * @property-read int|null $immediate_descendants_count
 * @property-read ProductCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain\Catalog\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \App\Domain\Catalog\Database\Factories\ProductCategoryFactory factory(...$parameters)
 * @method static Builder|ProductCategory hasLimitedDepth()
 * @method static Builder|ProductCategory limitDepth($limit)
 * @method static Builder|ProductCategory newModelQuery()
 * @method static Builder|ProductCategory newQuery()
 * @method static Builder|ProductCategory query()
 * @method static Builder|ProductCategory visible()
 * @method static Builder|ProductCategory whereCreatedAt($value)
 * @method static Builder|ProductCategory whereDepth($value)
 * @method static Builder|ProductCategory whereDescription($value)
 * @method static Builder|ProductCategory whereId($value)
 * @method static Builder|ProductCategory whereIsVisible($value)
 * @method static Builder|ProductCategory whereLeft($value)
 * @method static Builder|ProductCategory whereParentId($value)
 * @method static Builder|ProductCategory whereRight($value)
 * @method static Builder|ProductCategory whereSlug($value)
 * @method static Builder|ProductCategory whereTitle($value)
 * @method static Builder|ProductCategory whereUpdatedAt($value)
 * @method static Builder|ProductCategory withoutNode($node)
 * @method static Builder|ProductCategory withoutRoot()
 * @method static Builder|ProductCategory withoutSelf()
 * @mixin \Eloquent
 */
class ProductCategory extends Model
{
    use HasFactory;
    use HasSlug;
    use Node;

    public const MAX_DEPTH = 3;

    protected string $orderColumnName = 'title';
    protected $fillable = [
        'slug',
        'title',
        'description',
        'is_visible',
    ];

    public static Collection $hierarchy;

    /*
     * Relations
     * */

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories_products', 'category_id', 'product_id')->withTimestamps();
    }

    /*
     * Attributes
     * */

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getOverallProductsCountAttribute(): int
    {
        return self::mapHierarchy(static fn (ProductCategory $category): ?int => $category->products_count, collect([self::findInHierarchy($this->id)]))->sum();
    }

    public function getPathAttribute(): string
    {
        $path = [];
        $category = self::findInHierarchy($this->id)?->parent;
        while (isset($category)) {
            $path[] = $category->title;
            $category = ($category->parent_id === null) ? null : self::findInHierarchy($category->parent_id);
        }
        $path[] = $this->title;

        return implode(' — ', array_reverse($path));
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): ProductCategoryFactory
    {
        return ProductCategoryFactory::new();
    }

    public static function loadLightHierarchy(): void
    {
        self::$hierarchy = self::query()->hasLimitedDepth()->select(['id', 'parent_id', 'title', 'slug', 'is_visible'])->get()->toHierarchy();
    }

    public static function loadHeavyHierarchy(): void
    {
        self::$hierarchy = self::query()->hasLimitedDepth()->with(['parent'])->withCount(['products'])->get()->toHierarchy();
    }

    public static function findInHierarchy(int $id): ?self
    {
        $categories = self::$hierarchy;
        $category = null;
        while ($categories->isNotEmpty() && $category === null) {
            $category = $categories->filter(fn (self $category): bool => $category->id === $id)->first();
            if ($category === null) {
                $categories = $categories->map->children->flatten();
            }
        }

        return $category;
    }

    public static function filterHierarchy(Closure $filter, Collection $hierarchy): Collection
    {
        return $hierarchy->filter(function (self $item) use ($filter): bool {
            if ($filter($item)) {
                $item->setRelation('children', self::filterHierarchy($filter, $item->children));

                return true;
            }

            return false;
        });
    }

    public static function mapHierarchy(Closure $map, Collection $hierarchy): Collection
    {
        return $hierarchy
            ->map(fn (self $item): Collection => collect($map($item))->merge(self::mapHierarchy($map, $item->children)))
            ->flatten();
    }

    public static function getVisibleHierarchy(): Collection
    {
        return self::filterHierarchy(static fn (self $category): bool => $category->is_visible, self::$hierarchy);
    }

    /*
     * Scopes
     * */

    public function scopeHasLimitedDepth(Builder|Model $query): void
    {
        /** @phpstan-ignore-next-line  */
        $query->limitDepth(self::MAX_DEPTH);
    }

    public function scopeVisible(Builder $query): void
    {
        $query->whereIntegerInRaw('product_categories.id', self::mapHierarchy(static fn (self $category) => $category->id, self::getVisibleHierarchy()));
    }
}

<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Database\Factories\ProductCategoryFactory;
use App\Domains\Catalog\Enums\Media\ProductCategoryMediaCollectionKey;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use Baum\NestedSet\Node;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Domains\Catalog\Models\ProductCategory
 *
 * @property int                             $id
 * @property string                          $title
 * @property string                          $slug
 * @property string|null                     $description
 * @property bool                            $is_visible
 * @property int|null                        $parent_id
 * @property int                             $left
 * @property int                             $right
 * @property int|null                        $depth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $baseMedia
 * @property-read int|null $base_media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null $children_count
 * @property-read int $overall_products_count
 * @property-read string $path
 * @property-read int|null $products_count
 * @property-read \App\Components\Mediable\Models\Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $immediateDescendants
 * @property-read int|null $immediate_descendants_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read ProductCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Catalog\Models\Product[] $products
 *
 * @method static \App\Domains\Catalog\Database\Factories\ProductCategoryFactory factory(...$parameters)
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
final class ProductCategory extends Model implements HasMedia
{
    use HasExtendedFunctionality;
    use HasFactory;
    use HasSlug;
    use Node;
    use InteractsWithMedia {
        media as baseMedia;
    }

    public const MAX_DEPTH = 3;

    protected const HIERARCHY_CACHE_KEY = 'hierarchy';

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

    public function media(): MorphMany
    {
        return $this->baseMedia()->orderBy('order_column');
    }

    public function images(): MorphMany
    {
        return $this->media()->where('collection_name', ProductCategoryMediaCollectionKey::IMAGES->value);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->where('collection_name', ProductCategoryMediaCollectionKey::IMAGES->value);
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
        return self::mapHierarchy(static fn (ProductCategory $category): Collection => $category->products->pluck('id'), collect([self::findInHierarchy($this->id, self::getHierarchy())]))->unique()->count();
    }

    public function getProductsCountAttribute(): int
    {
        $category = self::findInHierarchy($this->id, self::getHierarchy());

        return ($category === null) ? 0 : $category->products->count();
    }

    public function getPathAttribute(): string
    {
        $path = [];
        $category = self::findInHierarchy($this->id, self::getHierarchy())?->parent;
        while (isset($category)) {
            $path[] = $category->title;
            $category = ($category->parent_id === null) ? null : self::findInHierarchy($category->parent_id, self::getHierarchy());
        }
        $path[] = $this->title;

        return implode(' — ', $path);
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): ProductCategoryFactory
    {
        return ProductCategoryFactory::new();
    }

    public static function loadHierarchy(): void
    {
        $hierarchy = self::query()
            ->hasLimitedDepth()
            ->with([
                'parent',
                'products' => fn (BelongsToMany $query): BelongsToMany => $query->select(['products.id']),
                'images.model',
            ])
            ->get()
            ->toHierarchy();

        Redis::set(self::getHierarchyCacheKey(), $hierarchy);

        self::$hierarchy = $hierarchy;
    }

    public static function getHierarchy(): Collection
    {
        if (isset(self::$hierarchy)) {
            return self::$hierarchy;
        }

        if (Redis::exists(self::getHierarchyCacheKey())) {
            /** @var Collection $hierarchy */
            $hierarchy = Redis::get(self::getHierarchyCacheKey());

            self::$hierarchy = $hierarchy;
        } else {
            self::loadHierarchy();
        }

        return self::getHierarchy();
    }

    public static function findInHierarchy(int $id, Collection $hierarchy): ?self
    {
        $category = null;
        while ($hierarchy->isNotEmpty() && $category === null) {
            $category = $hierarchy->filter(fn (self $category): bool => $category->id === $id)->first();
            if ($category === null) {
                $hierarchy = $hierarchy->map->children->flatten();
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
            ->filter()
            ->map(fn (self $item): Collection => collect($map($item))->merge(self::mapHierarchy($map, $item->children)))
            ->flatten();
    }

    public static function getVisibleHierarchy(): Collection
    {
        return self::filterHierarchy(static fn (self $category): bool => $category->is_visible, self::getHierarchy());
    }

    protected static function getHierarchyCacheKey(): string
    {
        return sprintf('%s:%s', self::class, self::HIERARCHY_CACHE_KEY);
    }

    /*
     * Scopes
     * */

    public function scopeHasLimitedDepth(Builder|Model $query): void
    {
        /** @phpstan-ignore-next-line */
        $query->limitDepth(self::MAX_DEPTH);
    }

    public function scopeVisible(Builder $query): void
    {
        $query->whereIntegerInRaw('product_categories.id', self::mapHierarchy(static fn (self $category) => $category->id, self::getVisibleHierarchy()));
    }
}

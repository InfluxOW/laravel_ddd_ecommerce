<?php

namespace App\Domains\Catalog\Models;

use App\Components\Mediable\Models\Media;
use App\Domains\Catalog\Database\Builders\ProductCategoryBuilder;
use App\Domains\Catalog\Database\Factories\ProductCategoryFactory;
use App\Domains\Catalog\Enums\Media\ProductCategoryMediaCollectionKey;
use App\Domains\Catalog\Jobs\Export\ProductCategoriesExportJob;
use App\Domains\Common\Interfaces\Exportable;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use App\Domains\Common\Traits\Models\Searchable;
use Baum\NestedSet\MoveNotPossibleException;
use Baum\NestedSet\Node;
use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Domains\Catalog\Models\ProductCategory
 *
 * @property int         $id
 * @property string      $title
 * @property string      $slug
 * @property string|null $description
 * @property bool        $is_visible
 * @property bool        $is_displayable
 * @property int|null    $parent_id
 * @property int         $left
 * @property int         $right
 * @property int|null    $depth
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read MediaCollection|Media[]                                    $baseMedia
 * @property-read int|null                                                   $base_media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null                                                   $children_count
 * @property-read int                                                        $overall_products_count
 * @property-read string                                                     $path
 * @property-read int|null                                                   $products_count
 * @property-read string                                                     $products_string
 * @property-read Media|null                                                 $image
 * @property-read MediaCollection|Media[]                                    $images
 * @property-read int|null                                                   $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $immediateDescendants
 * @property-read int|null                                                   $immediate_descendants_count
 * @property-read MediaCollection|Media[]                                    $media
 * @property-read int|null                                                   $media_count
 * @property-read ProductCategory|null                                       $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[]         $products
 *
 * @method static ProductCategoryBuilder|ProductCategory displayable()
 * @method static ProductCategoryFactory                 factory(...$parameters)
 * @method static ProductCategoryBuilder|ProductCategory hasLimitedDepth()
 * @method static ProductCategoryBuilder|ProductCategory limitDepth($limit)
 * @method static ProductCategoryBuilder|ProductCategory newModelQuery()
 * @method static ProductCategoryBuilder|ProductCategory newQuery()
 * @method static ProductCategoryBuilder|ProductCategory query()
 * @method static ProductCategoryBuilder|ProductCategory search(string $searchable, bool $orderByScore)
 * @method static ProductCategoryBuilder|ProductCategory whereCreatedAt($value)
 * @method static ProductCategoryBuilder|ProductCategory whereDepth($value)
 * @method static ProductCategoryBuilder|ProductCategory whereDescription($value)
 * @method static ProductCategoryBuilder|ProductCategory whereId($value)
 * @method static ProductCategoryBuilder|ProductCategory whereIsDisplayable($value)
 * @method static ProductCategoryBuilder|ProductCategory whereIsVisible($value)
 * @method static ProductCategoryBuilder|ProductCategory whereLeft($value)
 * @method static ProductCategoryBuilder|ProductCategory whereParentId($value)
 * @method static ProductCategoryBuilder|ProductCategory whereRight($value)
 * @method static ProductCategoryBuilder|ProductCategory whereSlug($value)
 * @method static ProductCategoryBuilder|ProductCategory whereTitle($value)
 * @method static ProductCategoryBuilder|ProductCategory whereUpdatedAt($value)
 * @method static ProductCategoryBuilder|ProductCategory withoutNode($node)
 * @method static ProductCategoryBuilder|ProductCategory withoutRoot()
 * @method static ProductCategoryBuilder|ProductCategory withoutSelf()
 *
 * @mixin \Eloquent
 */
final class ProductCategory extends Model implements HasMedia, Exportable
{
    use HasExtendedFunctionality;
    use HasFactory;
    use HasSlug;
    use Node;
    use InteractsWithMedia {
        media as baseMedia;
    }
    use Searchable;

    public const MAX_DEPTH = 3;

    protected const HIERARCHY_CACHE_KEY = 'hierarchy';

    protected string $orderColumnName = 'left';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'is_visible',
    ];

    protected $appends = ['path'];

    public static Collection $hierarchy;

    public static bool $reloadHierarchyOnSavedEvent = true;

    /*
     * Internal
     * */

    protected static function newFactory(): ProductCategoryFactory
    {
        return ProductCategoryFactory::new();
    }

    public function newEloquentBuilder($query): ProductCategoryBuilder
    {
        /** @var ProductCategoryBuilder<self> $builder */
        $builder = new ProductCategoryBuilder($query);

        return $builder;
    }

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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
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

        return $category === null ? 0 : $category->products->count();
    }

    public function getPathAttribute(): string
    {
        $path = [];
        $category = self::findInHierarchy($this->id, self::getHierarchy())?->parent;
        while (isset($category)) {
            $path[] = $category->title;
            $category = $category->parent_id === null ? null : self::findInHierarchy($category->parent_id, self::getHierarchy());
        }

        $path = array_reverse($path);
        $path[] = $this->title;

        return implode(' — ', $path);
    }

    public function getProductsStringAttribute(): string
    {
        return $this->products->implode(fn (Product $product): string => $product->title, ',' . PHP_EOL);
    }

    /*
     * Helpers
     * */

    public static function loadHierarchy(): void
    {
        $hierarchy = self::query()
            ->hasLimitedDepth()
            ->with([
                'parent',
                'products' => fn (BelongsToMany $query) => $query->select(['products.id']),
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

        return self::$hierarchy;
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

    public static function reorderHierarchy(array $updates, int $depth = 0, ?self $parent = null): void
    {
        $hierarchy = self::getHierarchy();

        $categoriesById = [];
        foreach ($updates as $i => $update) {
            /** @var self $category */
            $category = self::findInHierarchy($update['id'], $hierarchy);
            $categoriesById[$category->id] = $category;

            /** @var Collection $siblings */
            $siblings = $depth === 0 ? $hierarchy->values() : $category->parent?->children->values();
            $orderChanged = count($siblings) < $i + 1 || $siblings->get($i)->id !== $update['id'];

            if ($orderChanged) {
                try {
                    match (true) {
                        $i === 0 && $depth === 0 => $category->moveToLeftOf($hierarchy->first()),
                        $i === 0 && $depth > 0 => $category->makeFirstChildOf($parent),
                        $i > 0 => $category->moveToRightOf($categoriesById[$updates[$i - 1]['id']]),
                        default => true,
                    };
                } catch (MoveNotPossibleException) {
                }
            }

            $children = $update['children'] ?? [];

            if (count($children) > 0) {
                self::reorderHierarchy($children, $depth + 1, $category);
            }
        }
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
     * Searchable
     * */

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }

    /*
     * Exportable
     * */

    public static function getExportJob(): string
    {
        return ProductCategoriesExportJob::class;
    }
}

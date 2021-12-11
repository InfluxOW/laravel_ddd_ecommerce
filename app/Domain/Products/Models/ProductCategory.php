<?php

namespace App\Domain\Products\Models;

use App\Domain\Products\Database\Factories\ProductCategoryFactory;
use Baum\NestedSet\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\ProductCategory
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $parent_id
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null $children_count
 * @property-read int $overall_products_count
 * @property-read string|null $path
 * @property-read string $table_title
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $immediateDescendants
 * @property-read int|null $immediate_descendants_count
 * @property-read ProductCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain\Products\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \App\Domain\Products\Database\Factories\ProductCategoryFactory factory(...$parameters)
 * @method static Builder|ProductCategory limitDepth($limit)
 * @method static Builder|ProductCategory newModelQuery()
 * @method static Builder|ProductCategory newQuery()
 * @method static Builder|ProductCategory query()
 * @method static Builder|ProductCategory whereCreatedAt($value)
 * @method static Builder|ProductCategory whereDepth($value)
 * @method static Builder|ProductCategory whereId($value)
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
        'title',
        'slug',
    ];

    public static Collection $hierarchy;

    /*
     * Relations
     * */

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
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

    public function getProductsCount(): int
    {
        return $this->products_count + $this->children->sum(function (ProductCategory $category): int {
            return $category->getProductsCount();
        });
    }

    public function getOverallProductsCountAttribute(): int
    {
        $category = self::findInHierarchy($this->id);

        return ($category === null) ? 0 : $category->getProductsCount();
    }

    public function getTableTitleAttribute(): string
    {
        return str_repeat('⇒ ', $this->depth ?? 0) . $this->title;
    }

    public function getPathAttribute(): ?string
    {
        $path = [];
        $category = self::findInHierarchy($this->id)?->parent;
        while (isset($category)) {
            $path[] = $category->title;
            $category = $category->parent;
        }

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
        self::$hierarchy = self::$hierarchy ?? self::query()->with(['parent'])->withCount(['products'])->get()->toHierarchy();
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
}

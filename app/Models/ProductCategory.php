<?php

namespace App\Models;

use Baum\NestedSet\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $immediateDescendants
 * @property-read int|null $immediate_descendants_count
 * @property-read ProductCategory|null $parent
 * @method static \Database\Factories\ProductCategoryFactory factory(...$parameters)
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

    /*
     * Attributes
     * */

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}

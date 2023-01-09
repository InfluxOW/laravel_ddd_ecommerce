<?php

namespace App\Domains\News\Models;

use App\Components\Mediable\Models\Media;
use App\Domains\Catalog\Enums\Media\ProductMediaCollectionKey;
use App\Domains\Common\Interfaces\Exportable;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use App\Domains\Common\Traits\Models\Searchable;
use App\Domains\News\Database\Builders\ArticleBuilder;
use App\Domains\News\Database\Factories\ArticleFactory;
use App\Domains\News\Jobs\Export\NewsExportJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Domains\News\Models\Article
 *
 * @property int         $id
 * @property string      $title
 * @property string      $slug
 * @property string      $description
 * @property string      $body
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read MediaCollection|Media[] $baseMedia
 * @property-read int|null                $base_media_count
 * @property-read Media|null              $image
 * @property-read MediaCollection|Media[] $images
 * @property-read int|null                $images_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null                $media_count
 *
 * @method static ArticleFactory         factory(...$parameters)
 * @method static ArticleBuilder|Article newModelQuery()
 * @method static ArticleBuilder|Article newQuery()
 * @method static ArticleBuilder|Article published()
 * @method static ArticleBuilder|Article query()
 * @method static ArticleBuilder|Article search(string $searchable, bool $orderByScore)
 * @method static ArticleBuilder|Article unpublished()
 * @method static ArticleBuilder|Article whereBody($value)
 * @method static ArticleBuilder|Article whereCreatedAt($value)
 * @method static ArticleBuilder|Article whereDescription($value)
 * @method static ArticleBuilder|Article whereId($value)
 * @method static ArticleBuilder|Article wherePublishedAfter(\Carbon\Carbon $date)
 * @method static ArticleBuilder|Article wherePublishedAt($value)
 * @method static ArticleBuilder|Article wherePublishedBefore(\Carbon\Carbon $date)
 * @method static ArticleBuilder|Article wherePublishedBetween(?\Carbon\Carbon $min, ?\Carbon\Carbon $max)
 * @method static ArticleBuilder|Article whereSlug($value)
 * @method static ArticleBuilder|Article whereTitle($value)
 * @method static ArticleBuilder|Article whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Article extends Model implements HasMedia, Exportable
{
    use HasExtendedFunctionality;
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia {
        media as baseMedia;
    }
    use Searchable;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'description',
        'body',
        'published_at',
    ];

    protected $dates = [
        'published_at',
    ];

    /*
     * Relations
     * */

    public function media(): MorphMany
    {
        return $this->baseMedia()->orderBy('order_column');
    }

    public function images(): MorphMany
    {
        return $this->media()->where('collection_name', ProductMediaCollectionKey::IMAGES->value);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->where('collection_name', ProductMediaCollectionKey::IMAGES->value);
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

    /*
     * Internal
     * */

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    public function newEloquentBuilder($query): ArticleBuilder
    {
        /** @var ArticleBuilder<self> $builder */
        $builder = new ArticleBuilder($query);

        return $builder;
    }

    /*
     * Searchable
     * */

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'body' => $this->body,
            'published_at' => $this->published_at,
        ];
    }

    /*
     * Exportable
     * */

    public static function getExportJob(): string
    {
        return NewsExportJob::class;
    }
}

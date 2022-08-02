<?php

namespace App\Domains\News\Models;

use App\Domains\Catalog\Enums\Media\ProductMediaCollectionKey;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Traits\Models\Searchable;
use App\Domains\News\Database\Factories\ArticleFactory;
use App\Domains\News\Jobs\Export\NewsExportJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use JeroenG\Explorer\Application\Explored;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Domains\News\Models\Article
 *
 * @property int                             $id
 * @property string                          $title
 * @property string                          $slug
 * @property string                          $description
 * @property string                          $body
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $baseMedia
 * @property-read int|null $base_media_count
 * @property-read \App\Components\Mediable\Models\Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $images
 * @property-read int|null $images_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \App\Domains\News\Database\Factories\ArticleFactory factory(...$parameters)
 * @method static Builder|Article newModelQuery()
 * @method static Builder|Article newQuery()
 * @method static Builder|Article published()
 * @method static Builder|Article query()
 * @method static Builder|Article search(string $searchable, bool $orderByScore)
 * @method static Builder|Article unpublished()
 * @method static Builder|Article whereBody($value)
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereDescription($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article wherePublishedAfter(?\Carbon\Carbon $date)
 * @method static Builder|Article wherePublishedAt($value)
 * @method static Builder|Article wherePublishedBefore(?\Carbon\Carbon $date)
 * @method static Builder|Article wherePublishedBetween(?\Carbon\Carbon $min, ?\Carbon\Carbon $max)
 * @method static Builder|Article whereSlug($value)
 * @method static Builder|Article whereTitle($value)
 * @method static Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model implements HasMedia, Explored, Exportable
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
     * Helpers
     * */

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    /*
     * Scopes
     * */

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', Carbon::now());
    }

    public function scopeUnpublished(Builder $query): void
    {
        $query->whereNull('published_at');
    }

    public function scopeWherePublishedAfter(Builder $query, ?Carbon $date): void
    {
        $query->where('published_at', '>=', $date);
    }

    public function scopeWherePublishedBefore(Builder $query, ?Carbon $date): void
    {
        $query->where('published_at', '<=', $date);
    }

    public function scopeWherePublishedBetween(Builder|Article $query, ?Carbon $min, ?Carbon $max): void
    {
        if (isset($min)) {
            $query->wherePublishedAfter($min);
        }

        if (isset($max)) {
            $query->wherePublishedBefore($max);
        }
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

    public function mappableAs(): array
    {
        return [
            'title' => 'text',
            'slug' => 'text',
            'description' => 'text',
            'body' => 'text',
            'published_at' => 'date',
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

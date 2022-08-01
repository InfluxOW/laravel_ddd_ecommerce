<?php

namespace App\Domains\News\Models\Virtual\Article;

use App\Components\Mediable\Models\Virtual\Media;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class LightArticle
{
    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example beer-truck-collapse
     */
    public $slug;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example Semi Truck Hauling Beer Collapses, Causing Traffic Mess On I-76
     */
    public $title;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example http://localhost:8085/api/news/beer-truck-collapse
     */
    public $url;

    /**
     * @OA\Property(ref="#/components/schemas/Media")
     *
     * @var Media
     */
    public $image;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example An improperly loaded beer truck forced a closure of eastbound I-76 after the morning rush hour Friday, according to the Arvada Police Department.
     */
    public $description;

    /**
     * @OA\Property(format="date-time")
     *
     * @var string
     *
     * @example 2022-02-05T04:21:52+00:00
     */
    public $published_at;
}

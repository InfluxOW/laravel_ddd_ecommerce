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
     * @example beer-truck-collapse
     */
    public string $slug;

    /**
     * @OA\Property()
     *
     * @example Semi Truck Hauling Beer Collapses, Causing Traffic Mess On I-76
     */
    public string $title;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/news/beer-truck-collapse
     */
    public string $url;

    /**
     * @OA\Property(ref="#/components/schemas/Media")
     */
    public Media $image;

    /**
     * @OA\Property()
     *
     * @example An improperly loaded beer truck forced a closure of eastbound I-76 after the morning rush hour Friday, according to the Arvada Police Department.
     */
    public string $description;

    /**
     * @OA\Property(format="date-time")
     *
     * @example 2022-02-05T04:21:52+00:00
     */
    public string $published_at;
}

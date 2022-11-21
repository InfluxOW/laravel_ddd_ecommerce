<?php

namespace App\Domains\News\Models\Virtual\Article;

use App\Components\Mediable\Models\Virtual\Media;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class HeavyArticle extends LightArticle
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Media")
     * )
     *
     * @var Media[]
     */
    public array $images;

    /**
     * @OA\Property()
     *
     * @example An improperly loaded beer truck forced a closure of eastbound I-76 after the morning rush hour Friday, according to the Arvada Police Department.
     * I-76 was restricted to one lane at Wadsworth Boulevard because Arvada police said a semi-truck had too much beer in its load. The road reopened around 1:45 p.m.
     * The heavy load appeared to have caused the truck to cave in, according to photos Arvada police posted of the incident.
     */
    public string $body;
}

<?php

namespace App\Interfaces\Http\Controllers;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Components\Queryable\Enums\QueryKey;
use App\Components\Queryable\Services\Filter\FilterQueryResourceBuilder;
use App\Components\Queryable\Services\Sort\SortQueryResourceBuilder;
use App\Domains\Generic\Enums\Response\ResponseKey;
use App\Domains\Generic\Http\Requests\IndexRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\OpenApi(
 *    @OA\Info(
 *       title="E-Commerce App API",
 *       description="E-Commerce App",
 *       version="1.0.0",
 *       @OA\Contact(
 *          email="krochak_n@mail.ru",
 *          url="https://github.com/InfluxOW"
 *       ),
 *    ),
 *    @OA\Server(
 *       url=L5_SWAGGER_CONST_HOST,
 *       description="E-Commerce App API Server",
 *       @OA\ServerVariable(
 *          serverVariable="schema",
 *          enum={"https", "http"},
 *          default="https",
 *       ),
 *    ),
 * )
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ResponseTrait;

    /**
     * @param class-string<JsonResource> $resource
     */
    protected function respondPaginated(string $resource, QueryBuilder|Builder $query, IndexRequest $request, ?FilterService $filterService = null, ?SortService $sortService = null): AnonymousResourceCollection
    {
        if ($query instanceof Builder) {
            $query = QueryBuilder::for($query);
        }

        $validated = $request->validated();
        $additional = [];

        if (isset($filterService)) {
            $filterService->build();

            $query->allowedFilters($filterService->callbacks()->toArray());

            $additional[ResponseKey::QUERY->value][QueryKey::FILTER->value] = (new FilterQueryResourceBuilder($filterService))->resource($request);
        }

        if (isset($sortService)) {
            $sortService->build();

            $query
                ->allowedSorts($sortService->callbacks()->toArray())
                ->defaultSort($sortService->callbacks()->first());

            $additional[ResponseKey::QUERY->value][QueryKey::SORT->value] = (new SortQueryResourceBuilder($sortService))->resource($request);
        }

        $items = $query
            ->paginate($validated[QueryKey::PER_PAGE->value], ['*'], QueryKey::PAGE->value, $validated[QueryKey::PAGE->value])
            ->appends($request->append());

        return $this->respondWithCollection($resource, $items, $additional);
    }
}

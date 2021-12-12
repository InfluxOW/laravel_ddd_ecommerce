<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ResponseTrait
{
    /**
     * The current path of resource to respond
     *
     * @var class-string<JsonResource>
     */
    protected string $resource;

    protected function respondWithMessage(string $message, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    protected function respondWithCustomData(array $data, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    protected function respondWithCollection(Collection|LengthAwarePaginator $collection, array $additional = []): AnonymousResourceCollection
    {
        return $this->resource::collection($collection)->additional($additional);
    }

    protected function respondWithItem(Model $item, array $additional = []): JsonResource
    {
        return $this->resource::make($item)->additional($additional);
    }
}

<?php

namespace App\Interfaces\Http\Controllers;

use App\Components\Generic\Enums\Response\ResponseKey;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ResponseTrait
{
    protected function respondWithMessage(string $message, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json([ResponseKey::MESSAGE->value => $message], $status);
    }

    protected function respondWithCustomData(array $data, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    protected function respondSuccess(): JsonResponse
    {
        return response()->json([], SymfonyResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param class-string<JsonResource> $resource
     */
    protected function respondWithCollection(string $resource, Collection|LengthAwarePaginator $collection, array $additional = []): AnonymousResourceCollection
    {
        return $resource::collection($collection)->additional($additional);
    }

    /**
     * @param class-string<JsonResource> $resource
     */
    protected function respondWithItem(string $resource, Model $item, array $additional = []): JsonResource
    {
        return $resource::make($item)->additional($additional);
    }
}

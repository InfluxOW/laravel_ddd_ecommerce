<?php

namespace App\Interfaces\Http\Controllers;

use App\Domains\Common\Enums\Response\ResponseKey;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ResponseTrait
{
    protected function respondWithCustomData(array $data, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    protected function respondWithMessage(string $message, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return $this->respondWithCustomData([ResponseKey::MESSAGE->value => $message], $status);
    }

    protected function respondWithStatus(int $status): JsonResponse
    {
        return $this->respondWithCustomData([], $status);
    }

    protected function respondSuccess(): JsonResponse
    {
        return $this->respondWithStatus(SymfonyResponse::HTTP_OK);
    }

    protected function respondNotFound(): JsonResponse
    {
        return $this->respondWithStatus(SymfonyResponse::HTTP_NOT_FOUND);
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
    protected function respondWithPossiblyNotFoundItem(string $resource, Builder $query, array $additional = []): JsonResource|JsonResponse
    {
        $item = $query->first();

        return $item === null ? $this->respondNotFound() : $resource::make($item)->additional($additional);
    }
}

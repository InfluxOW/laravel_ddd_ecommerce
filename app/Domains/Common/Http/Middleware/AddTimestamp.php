<?php

namespace App\Domains\Common\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AddTimestamp
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response->setData(array_merge_recursive($response->getData(assoc: true), ['meta' => ['timestamp' => intdiv((int) Carbon::now()->format('Uu'), 1000)]]));
        }

        return $response;
    }
}

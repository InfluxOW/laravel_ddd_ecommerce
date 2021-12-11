<?php

namespace App\Application\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddTimestamp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var JsonResponse $response */
        $response = $next($request);
        $response->setData(array_merge_recursive($response->getData(true), ['meta' => ['timestamp' => intdiv((int) Carbon::now()->format('Uu'), 1000)]]));

        return $response;
    }
}

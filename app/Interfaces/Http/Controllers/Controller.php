<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
}

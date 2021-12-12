<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="App API",
 *    description="App",
 *    version="1.0.0",
 *    @OA\Contact(
 *       email="krochak_n@mail.ru",
 *       url="https://github.com/InfluxOW"
 *    ),
 * ),
 *  @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="App API Server"
 * ),
 *  @OA\SecurityScheme(
 *     securityScheme="access_token",
 *     type="http",
 *     scheme="bearer",
 *     in="header",
 *     name="Authorization",
 *     bearerFormat="JWT"
 *  ),
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ResponseTrait;
}

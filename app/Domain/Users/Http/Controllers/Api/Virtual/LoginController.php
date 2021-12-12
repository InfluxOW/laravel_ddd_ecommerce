<?php

namespace App\Domain\Users\Http\Controllers\Api\Virtual;

class LoginController
{
    /**
     * @OA\Post(
     * path="/login",
     * summary="Sign In",
     * description="Login by email and password",
     * operationId="authLogin",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful login",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMDM2N2RkZTczY2NiYmE2ZjRkYTkxY2VjOWRlODA0NmYzYjNkYTI5MWRlZjM4Mjg1OTliZTY5YzAxOTUyMTIwMGIwYTU1ZGM5NWEyMzM2NmUiLCJpYXQiOjE1OTg1Mjg1NTYsIm5iZiI6MTU5ODUyODU1NiwiZXhwIjoxNjMwMDY0NTU2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.BfFE40nkUt-Jwr7S8gCvogsCoYsX6VSYvVzuT9czVuGS_VeDFV4W2riQ0SopOeLtsvZttMI8VLgpXDYBYsASsFo4H-zrAmGRYZ2qLi4QNj5cPVJpXqe4xhRQsYbpY4xre7lNfXaYod56Ocek-3psN3A68eTZ2ro_cPde42lkur5eQTx7-TojHeqpK3uywl8IugzvBq3wcfcHLRQZSmEVvkJzYNuLs-03YsMnyod1wk3FszqzqzmZP2hiTXj-HhU1N6WRy6XobGzgoM__bxPRsQoMK3TCphqHIhwO14pJzaFbDqEf3USEMmPrF9rYJrgUjzGUglqRsg78GZsWHNakhH6-q1kibPI-k-VMazKSn85wi6HuXXCwycBXY0PRYpYAGbUrfBkuxK_t21peZ8tb6kD3XEr6XEz3PgEmbaRbnFelQEybjLCGYmWj2yuKOjkSQgNeEdOmpqzUDUiJByjE_ElxRD77prr-OG6e3GwwwOaHYLy6_8MtRP1cTg81BtcEYc8AmFeuceSAEjbDzEzaLSgiAJH9dh3Qy24V5HNnQjXmWvpSAHW1sJcXAYZyPE1bY0h7LBk91UmTJ_mkxa0EDofQsOFMhAQdGrQr2HRdrwfmD_vrHDNL-MeP94XNAihrVg4AXjhTMuZOhuzSxza_DgNO9EACQuzy1f81mfMapGU"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Login failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please, try again!"),
     *    ),
     * ),
     * @OA\Response(
     *    response=500,
     *    description="Login failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, something went wrong. Please, try again later!"),
     *   ),
     * ),
     * )
     */
    public function __invoke(): void
    {
        //
    }
}

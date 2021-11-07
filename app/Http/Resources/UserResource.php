<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="UserResource"),
 * @OA\Property(property="name", type="string", readOnly="true", example="John Doe"),
 * @OA\Property(property="email", type="string", readOnly="true", format="email", example="john_doe@gmail.com"),
 * )
 *
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User **/
        $user = $this->resource;

        return [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}

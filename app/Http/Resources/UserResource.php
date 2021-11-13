<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

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

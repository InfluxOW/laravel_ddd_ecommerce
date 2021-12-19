<?php

namespace App\Domain\Users\Http\Resources;

use App\Domain\Users\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class UserResource extends JsonResource
{
    #[ArrayShape(['name' => "string", 'email' => "string", 'phone' => "null|string", 'created_at' => "null|string"])]
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'created_at' => ($user->created_at === null) ? null : $user->created_at->format('d M Y H:i:s'),
        ];
    }
}

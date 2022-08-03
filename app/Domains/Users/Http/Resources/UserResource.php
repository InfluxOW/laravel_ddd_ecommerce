<?php

namespace App\Domains\Users\Http\Resources;

use App\Domains\Users\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

final class UserResource extends JsonResource
{
    #[ArrayShape(['name' => 'string', 'email' => 'string', 'phone' => 'string|null', 'created_at' => 'string|null'])]
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'created_at' => $user->created_at?->defaultFormat(),
        ];
    }
}

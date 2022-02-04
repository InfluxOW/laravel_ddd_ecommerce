<?php

namespace App\Domains\Users\Http\Resources;

use App\Domains\Users\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'created_at' => $user->created_at?->format('d M Y H:i:s'),
        ];
    }
}

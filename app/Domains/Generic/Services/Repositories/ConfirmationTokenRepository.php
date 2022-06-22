<?php

namespace App\Domains\Generic\Services\Repositories;

use App\Domains\Generic\Enums\ConfirmationTokenType;
use App\Domains\Generic\Exceptions\InvalidConfirmationTokenException;
use App\Domains\Generic\Models\ConfirmationToken;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class ConfirmationTokenRepository
{
    public function create(ConfirmationTokenType $type, User $user, Carbon $expiresAt): ConfirmationToken
    {
        return $user->confirmationTokens()->create(['type' => $type->name, 'token' => $this->createToken($type), 'expires_at' => $expiresAt]);
    }

    /**
     * @throws InvalidConfirmationTokenException
     */
    public function consume(User $user, ConfirmationTokenType $type, string $token): void
    {
        $confirmationToken = $this->find($user, $type, $token);

        if ($confirmationToken === null || $confirmationToken->isExpired() || $confirmationToken->isUsed()) {
            throw new InvalidConfirmationTokenException($confirmationToken);
        }

        $confirmationToken->update(['used_at' => Carbon::now()]);
    }

    private function find(User $user, ConfirmationTokenType $type, string $token): ?ConfirmationToken
    {
        $latestToken = ConfirmationToken::query()->whereBelongsTo($user)->where('type', $type->name)->latest()->first();

        return (isset($latestToken) && strtolower($latestToken->token) === strtolower($token)) ? $latestToken : null;
    }

    private function createToken(ConfirmationTokenType $type): string
    {
        return match ($type) {
            ConfirmationTokenType::EMAIL_VERIFICATION => $this->generateShortStringToken(),
        };
    }

    private function generateShortStringToken(): string
    {
        return Str::of(Str::random(6))->upper()->toString();
    }
}

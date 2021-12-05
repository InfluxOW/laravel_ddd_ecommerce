<?php

namespace App\Domain\Generic\Currency\Models;

final class Kopecks
{
    public const KOPECKS_IN_ROUBLE = 100;

    public function __construct(public readonly int $amount)
    {
    }

    public static function fromRoubles(float|int $value): self
    {
        return new self((int) ($value * self::KOPECKS_IN_ROUBLE));
    }

    public function roubles(): float
    {
        return (float) preg_replace('/[^\d.]/', '', number_format($this->amount / self::KOPECKS_IN_ROUBLE, 2));
    }
}

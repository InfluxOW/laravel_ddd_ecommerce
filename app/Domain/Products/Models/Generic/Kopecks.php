<?php

namespace App\Domain\Products\Models\Generic;

final class Kopecks
{
    public const KOPECKS_IN_ROUBLE = 100;

    /** @var int */
    public $amount;

    public function __construct(int $value)
    {
        $this->amount = $value;
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

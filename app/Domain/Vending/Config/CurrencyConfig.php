<?php

declare(strict_types=1);

namespace App\Domain\Vending\Config;

final class CurrencyConfig
{
    public function __construct(
        public readonly string $sign,
        public readonly string $space,
        public readonly string $position // before | after
    ) {}

    public function isAfter(): bool
    {
        return $this->position === 'after';
    }

    public function validate(): void
    {
        if (empty($this->sign)) {
            throw new \InvalidArgumentException("Currency sign is missing");
        }

        if (!in_array($this->position, ['before', 'after'], true)) {
            throw new \InvalidArgumentException("Invalid currency position");
        }

        if ($this->space === null) {
            throw new \InvalidArgumentException("Currency space must be defined");
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Currency
{
    public const CURRENCY_POSITION_BEFORE = 'before';
    public const CURRENCY_POSITION_AFTER = 'after';

    public function __construct(
        public readonly string $sign,
        public readonly string $space,
        public readonly string $position
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->sign === '') {
            throw new \InvalidArgumentException("Currency sign is required");
        }

        if (!in_array($this->position, [
            self::CURRENCY_POSITION_BEFORE,
            self::CURRENCY_POSITION_AFTER
        ], true)) {
            throw new \InvalidArgumentException("Invalid currency position");
        }
    }

    public function format(int $amount): string
    {
        $value = number_format($amount / 100, 2);

        return $this->position === self::CURRENCY_POSITION_AFTER
            ? $value . $this->space . $this->sign
            : $this->sign . $this->space . $value;
    }
}

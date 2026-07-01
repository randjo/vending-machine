<?php

declare(strict_types=1);

namespace App\Domain\Vending\Entities;

use InvalidArgumentException;

final class Money
{
    public function __construct(private int $amount) // в стотинки
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Money cannot be negative");
        }
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function add(Money $other): self
    {
        return new self($this->amount + $other->amount());
    }

    public function subtract(Money $other): self
    {
        return new self($this->amount - $other->amount());
    }

    public function isLessThan(Money $other): bool
    {
        return $this->amount < $other->amount();
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount();
    }
}

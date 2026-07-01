<?php

declare(strict_types=1);

namespace App\Domain\Vending\Entities;

final class Transaction
{
    public function __construct(
        public readonly string $drinkName,
        public readonly Money $price,
        public readonly \DateTimeImmutable $createdAt
    ) {}
}

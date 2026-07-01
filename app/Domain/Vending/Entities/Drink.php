<?php

declare(strict_types=1);

namespace App\Domain\Vending\Entities;

final class Drink
{
    public function __construct(
        public readonly string $name,
        public readonly Money $price
    ) {}
}

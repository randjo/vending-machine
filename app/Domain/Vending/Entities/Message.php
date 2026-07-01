<?php

declare(strict_types=1);

namespace App\Domain\Vending\Entities;

final class Message
{
    public function __construct(
        public readonly string $text,
        public readonly \DateTimeImmutable $time
    ) {}
}

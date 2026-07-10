<?php

declare(strict_types=1);

namespace App\Domain\Vending;

interface DisplayRepositoryInterface
{
    public function add(string $message): void;

    public function latest(int $limit = 3): array;

    public function reset(): void;
}

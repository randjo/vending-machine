<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class ArrayDisplayRepository implements DisplayRepositoryInterface
{
    private array $messages = [];

    public function add(string $message): void
    {
        $this->messages[] = $message;
    }

    public function latest(int $limit = 3): array
    {
        return array_slice($this->messages, -$limit);
    }

    public function reset(): void
    {
        $this->messages = [];
    }
}

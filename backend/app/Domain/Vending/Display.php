<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Display
{
    public function __construct(private DisplayRepositoryInterface $repository) {}

    public function add(string $message): void
    {
        $this->repository->add($message);
    }

    public function all(): string
    {
        return implode("\n", $this->repository->latest(PHP_INT_MAX));
    }

    public function getMessages(): array
    {
        return $this->repository->latest(PHP_INT_MAX);
    }

    public function latest(int $limit = 3): array
    {
        return $this->repository->latest($limit);
    }

    public function reset(): void
    {
        $this->repository->reset();
    }
}

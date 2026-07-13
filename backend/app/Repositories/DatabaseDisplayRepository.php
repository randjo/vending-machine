<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\DisplayMessage;
use App\Domain\Vending\DisplayRepositoryInterface;

final class DatabaseDisplayRepository implements DisplayRepositoryInterface
{
    public function add(string $message): void
    {
        DisplayMessage::create([
            'message' => $message
        ]);
    }

    public function latest(int $limit = 3): array
    {
        return DisplayMessage::query()
            ->latest('id')
            ->limit($limit)
            ->pluck('message')
            ->reverse()
            ->values()
            ->toArray();
    }

    public function reset(): void
    {
        DisplayMessage::truncate();
    }
}

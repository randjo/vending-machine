<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MachineState;
use App\Domain\Vending\WalletRepositoryInterface;

final class DatabaseWalletRepository implements WalletRepositoryInterface
{
    public function getBalance(): int
    {
        return MachineState::first()->balance ?? 0;
    }

    public function saveBalance(int $balance): void
    {
        $state = MachineState::firstOrCreate(
            [],
            [
                'balance' => 0
            ]
        );

        $state->update([
            'balance' => $balance
        ]);
    }

    public function reset(): void
    {
        $this->saveBalance(0);
    }
}

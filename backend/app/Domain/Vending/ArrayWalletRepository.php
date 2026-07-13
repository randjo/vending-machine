<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class ArrayWalletRepository implements WalletRepositoryInterface
{
    private int $balance;

    public function __construct(float $balance = 0)
    {
        $this->balance = Currency::toCents($balance);
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function saveBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function reset(): void
    {
        $this->balance = 0;
    }
}

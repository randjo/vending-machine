<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Wallet
{
    private int $balance;

    public function __construct(private Settings $settings, float $balance)
    {
        $this->balance = (int) ($balance * 100);
    }

    public function insertCoin(int $coin): bool
    {
        if (!in_array($coin, $this->settings->getAllowedCoins(), true)) {
            return false;
        }

        $this->balance += $coin;

        return true;
    }

    public function spend(int $amount): bool
    {
        if ($this->balance < $amount) {
            return false;
        }

        $this->balance -= $amount;

        return true;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getChange(): array
    {
        $amount = $this->balance;
        $result = [];

        foreach (array_reverse($this->settings->getAllowedCoins()) as $coin) {
            while ($amount >= $coin) {
                $result[$coin] = ($result[$coin] ?? 0) + 1;
                $amount -= $coin;
            }
        }

        $this->reset();

        return $result;
    }

    public function reset(): void
    {
        $this->balance = 0;
    }
}

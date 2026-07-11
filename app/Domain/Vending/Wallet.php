<?php

declare(strict_types=1);

namespace App\Domain\Vending;

use App\Domain\Vending\WalletRepositoryInterface;

final class Wallet
{
    public function __construct(private Settings $settings, private WalletRepositoryInterface $repository) {}

    /**
     * @var int $coin - coin in cents
     *
     * return bool
     */
    public function insertCoin(int $coin): bool
    {
        if (!in_array($coin, $this->settings->getAllowedCoins(), true)) {
            return false;
        }

        $this->repository->saveBalance($this->getBalance() + $coin);

        return true;
    }

    public function spend(int $amount): bool
    {
        if ($this->getBalance() < $amount) {
            return false;
        }

        $this->repository->saveBalance($this->getBalance() - $amount);

        return true;
    }

    public function getBalance(): int
    {
        return $this->repository->getBalance();
    }

    public function getChange(): array
    {
        $amount = $this->getBalance();
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
        $this->repository->reset();
    }
}

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
        $balance = $amount = $this->getBalance();
        $result = [];
        $change = 0;

        foreach (array_reverse($this->settings->getAllowedCoins()) as $coin) {
            while ($balance >= $coin) {
                $result[$coin] = ($result[$coin] ?? 0) + 1;
                $balance -= $coin;
            }
        }

        if ($balance > 0) {
            $this->save($balance);
        } else {
            $this->reset();
        }

        $change = $amount - $balance;

        return [$change, $result];
    }

    public function save(int $amount): void
    {
        $this->repository->saveBalance($amount);
    }

    public function reset(): void
    {
        $this->repository->reset();
    }
}

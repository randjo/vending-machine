<?php

declare(strict_types=1);

namespace App\Domain\Vending;

interface WalletRepositoryInterface
{
    public function getBalance(): int;

    public function saveBalance(int $balance): void;

    public function reset(): void;
}

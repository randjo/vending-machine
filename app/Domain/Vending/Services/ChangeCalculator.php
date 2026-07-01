<?php

declare(strict_types=1);

namespace App\Domain\Vending\Services;

final class ChangeCalculator
{
    /** @var int[] allowed coins in cents */
    private array $coins = [100, 50, 20, 10, 5];

    public function calculate(int $amount): array
    {
        $result = [];

        foreach ($this->coins as $coin) {
            while ($amount >= $coin) {
                $result[$coin] = ($result[$coin] ?? 0) + 1;
                $amount -= $coin;
            }
        }

        return $result;
    }
}

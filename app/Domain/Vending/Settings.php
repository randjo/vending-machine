<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Settings
{
    public function __construct(private array $drinks, private array $coins) {}

    public function addDrink(string $name, float $price): void
    {
        $this->drinks[$name] = Currency::toCents($price);
    }

    public function removeDrink(string $name): bool
    {
        if (!isset($this->drinks[$name])) {
            return false;
        }

        unset($this->drinks[$name]);

        return true;
    }

    public function getDrinks(): array
    {
        return $this->drinks;
    }

    public function getDrinkPrice(string $name): ?int
    {
        return $this->drinks[$name] ?? null;
    }

    public function addCoin(float $coin): void
    {
        $coin = Currency::toCents($coin);

        if (!in_array($coin, $this->coins, true)) {
            $this->coins[] = $coin;
            sort($this->coins);
        }
    }


    public function removeCoin(float $coin): bool
    {
        $coin = Currency::toCents($coin);

        $index = array_search(
            $coin,
            $this->coins,
            true
        );

        if ($index === false) {
            return false;
        }

        unset($this->coins[$index]);

        $this->coins = array_values($this->coins);

        return true;
    }

    public function getAllowedCoins(): array
    {
        return $this->coins;
    }
}

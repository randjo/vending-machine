<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Settings
{
    private array $drinks = [];
    private array $coins = [];

    public function __construct(array $drinks, array $coins)
    {
        foreach ($drinks as $name => $price) {
            $this->drinks[$name] = (int) ($price * 100);
        }

        $this->coins = array_map(
            fn($coin) => (int) ($coin * 100),
            $coins
        );
    }

    public function getDrinks(): array
    {
        return $this->drinks;
    }

    public function getAllowedCoins(): array
    {
        return $this->coins;
    }

    public function getDrinkPrice(string $name): ?int
    {
        foreach ($this->drinks as $key => $price) {
            if (strcmp($key, $name) === 0) {
                return $price;
            }
        }

        return null;
    }
}

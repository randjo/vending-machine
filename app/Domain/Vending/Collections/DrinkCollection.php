<?php

declare(strict_types=1);

namespace App\Domain\Vending\Collections;

use App\Domain\Vending\Entities\Drink;

final class DrinkCollection
{
    /** @var array<string, Drink> */
    private array $items = [];

    public function add(Drink $drink): void
    {
        $this->items[$drink->name] = $drink;
    }

    public function find(string $name): ?Drink
    {
        return $this->items[$name] ?? null;
    }

    /** @return array<string, Drink> */
    public function all(): array
    {
        return $this->items;
    }
}

<?php

namespace App\Repositories;

use App\Models\Drink;

final class DrinkRepository
{
    public function all(): array
    {
        return Drink::all()
            ->mapWithKeys(fn($drink) => [$drink->name => $drink->price])
            ->toArray();
    }

    public function create(string $name, int $price): Drink
    {
        return Drink::create([
            'name' => $name,
            'price' => $price,
        ]);
    }

    public function delete(int $id): int
    {
        return Drink::destroy($id);
    }
}

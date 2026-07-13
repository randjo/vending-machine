<?php

namespace App\Repositories;

use App\Models\Drink;
use Illuminate\Database\Eloquent\Collection;

final class DrinkRepository
{
    public function all(): array
    {
        return Drink::all()
            ->mapWithKeys(fn($drink) => [$drink->name => $drink->price])
            ->toArray();
    }

    public function allDrinksCollection(): Collection
    {
        return Drink::orderBy('name')->get();
    }

    public function create(string $name, int $price): Drink
    {
        return Drink::create([
            'name' => $name,
            'price' => $price,
        ]);
    }

    public function update(int $id, string $name, int $price): Drink
    {
        $drink = Drink::findOrFail($id);

        $drink->update([
            'name' => $name,
            'price' => $price,
        ]);

        return $drink;
    }

    public function delete(int $id): int
    {
        return Drink::destroy($id);
    }
}

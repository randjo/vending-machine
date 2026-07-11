<?php

namespace App\Repositories;

use App\Models\Coin;
use Illuminate\Database\Eloquent\Collection;

final class CoinRepository
{
    public function all(): array
    {
        return Coin::pluck('value')->toArray();
    }

    public function allCoinsCollection(): Collection
    {
        return Coin::orderBy('value')->get();
    }

    public function create(int $value): Coin
    {
        return Coin::create(['value' => $value]);
    }

    public function update(int $id, int $value): Coin
    {
        $coin = Coin::findOrFail($id);

        $coin->update(['value' => $value]);

        return $coin;
    }

    public function delete(int $id): int
    {
        return Coin::destroy($id);
    }
}

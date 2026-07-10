<?php

namespace App\Repositories;

use App\Models\Coin;


final class CoinRepository
{
    public function all(): array
    {
        return Coin::pluck('value')->toArray();
    }

    public function create(int $value): Coin
    {
        return Coin::create(['value' => $value]);
    }

    public function delete(int $id): int
    {
        return Coin::destroy($id);
    }
}

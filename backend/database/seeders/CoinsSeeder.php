<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Seeder;

class CoinsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([5, 10, 20, 50, 100] as $value) {
            Coin::updateOrCreate(
                ['value' => $value]
            );
        }
    }
}

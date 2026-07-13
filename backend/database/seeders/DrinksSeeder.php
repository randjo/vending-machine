<?php

namespace Database\Seeders;

use App\Models\Drink;
use Illuminate\Database\Seeder;

class DrinksSeeder extends Seeder
{
    public function run(): void
    {
        $drinks = [
            [
                'name' => 'Milk',
                'price' => 50,
            ],
            [
                'name' => 'Espresso',
                'price' => 40,
            ],
            [
                'name' => 'Long Espresso',
                'price' => 60,
            ],
            [
                'name' => 'Hot Chocolate',
                'price' => 50,
            ],
            [
                'name' => 'Tea',
                'price' => 30,
            ],
        ];

        foreach ($drinks as $drink) {
            Drink::updateOrCreate(
                ['name' => $drink['name']],
                ['price' => $drink['price']]
            );
        }
    }
}

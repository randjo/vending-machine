<?php

use App\Domain\Vending\Currency;
use App\Domain\Vending\VendingMachine;

require __DIR__ . '/vendor/autoload.php';

$machine = new VendingMachine(
    [
        'sign' => 'лв',
        'space' => '',
        'position' => Currency::CURRENCY_POSITION_AFTER,
    ],
    [
        'Milk' => 0.50,
        'Espresso' => 0.40,
        'Long Espresso' => 0.60,
    ],
    [
        'coins' => [
            0.05,
            0.10,
            0.20,
            0.50,
            1,
        ],
        'balance' => 0.00,
    ]
);

$machine
    ->buyDrink('espresso')
    ->buyDrink('Espresso')
    ->viewDrinks()
    ->putCoin(2)
    ->putCoin(1)
    ->buyDrink('Espresso')
    ->getCoins()
    ->viewAmount()
    ->getCoins();

echo $machine->display()->all();

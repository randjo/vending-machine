<?php

use App\Domain\Vending\VendingMachine;
use App\Domain\Vending\Currency;

it('runs full vending machine flow correctly', function () {
    $machine = new VendingMachine(
        [
            'sign' => 'лв.',
            'space' => '',
            'position' => Currency::CURRENCY_POSITION_AFTER,
        ],
        [
            'Milk' => 50,
            'Espresso' => 40,
            'Long Espresso' => 60,
        ],
        [
            'coins' => [5, 10, 20, 50, 100],
            'balance' => 0,
        ]
    );

    $output = $machine
        ->buyDrink('espresso')
        ->buyDrink('Espresso')
        ->viewDrinks()
        ->putCoin(1)
        ->putCoin(2)
        ->buyDrink('Espresso')
        ->getCoins()
        ->viewAmount()
        ->display()
        ->all();

    expect($output)->toBeStringBackedEnum();
    expect($output)->not->toBeEmpty();
});

<?php

use App\Domain\Vending\Currency;
use App\Domain\Vending\VendingMachine;

it('returns message when drink not found', function () {

    $machine = new VendingMachine(
        [
            'sign' => 'лв.',
            'space' => '',
            'position' => Currency::CURRENCY_POSITION_AFTER,
        ],
        [
            'Milk' => 50,
        ],
        [
            'coins' => [5, 10, 20, 50, 100],
            'balance' => 0,
        ]
    );

    $machine->buyDrink('Coffee');

    $output = $machine->display()->all();

    expect($output)->toContain('Исканият продукт не е намерен.');
});

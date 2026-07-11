<?php

use App\Domain\Vending\Currency;
use App\Domain\Vending\VendingMachine;

it('shows insufficient funds message', function () {

    $machine = new VendingMachine(
        [
            'sign' => 'лв.',
            'space' => '',
            'position' => Currency::CURRENCY_POSITION_AFTER,
        ],
        [
            'Espresso' => 40,
        ],
        [
            'coins' => [5, 10, 20, 50, 100],
            'balance' => 0,
        ]
    );

    $machine
        ->putCoin(0.10)
        ->buyDrink('Espresso');

    $output = $machine->display()->all();

    expect($output)->toContain('Недостатъчна наличност');
});

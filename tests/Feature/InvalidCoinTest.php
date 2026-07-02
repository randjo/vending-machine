<?php

use App\Domain\Vending\Currency;
use App\Domain\Vending\VendingMachine;

it('rejects invalid coin and shows allowed coins', function () {

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

    $machine->putCoin(7); // invalid coin

    $output = $machine->display()->all();

    expect($output)->toContain('Автоматът приема монети от:');
});

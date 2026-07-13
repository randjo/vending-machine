<?php

use App\Domain\Vending\Currency;
use App\Domain\Vending\VendingMachine;

it('calculates correct change using state instead of output parsing', function () {
    $machine = new VendingMachine(
        [
            'sign' => 'лв.',
            'space' => '',
            'position' => Currency::CURRENCY_POSITION_AFTER,
        ],
        [
            'Espresso' => 0.40,
        ],
        [
            'coins' => [0.05, 0.10, 0.20, 0.50, 1],
            'balance' => 0.00,
        ]
    );

    $machine->putCoin(1);
    $machine->buyDrink('Espresso');
    $machine->getCoins();
    $messages = $machine->display()->getMessages();

    expect($messages[0])->toContain('Успешно поставихте 1.00лв., текущата Ви сума е 1.00лв.');
    expect($messages[1])->toContain("Успешно закупихте 'Espresso' от 0.40лв., текущата Ви сума е 0.60лв.");
    expect($messages[2])->toContain("Получихте ресто 0.60лв. в монети от: 1x0.50лв., 1x0.10лв.");
});

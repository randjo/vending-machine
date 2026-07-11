<?php

use App\Domain\Vending\ArrayDisplayRepository;
use App\Domain\Vending\Display;

it('returns only last three display messages', function () {

    $display = new Display(new ArrayDisplayRepository());

    $display->add('Message 1');
    $display->add('Message 2');
    $display->add('Message 3');
    $display->add('Message 4');

    expect($display->latest())
        ->toBe([
            'Message 2',
            'Message 3',
            'Message 4',
        ]);
});

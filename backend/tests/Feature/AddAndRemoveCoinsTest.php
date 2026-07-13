<?php

use App\Domain\Vending\Settings;

it('can add and remove coins', function () {
    $settings = new Settings([], [0.05]);
    $settings->addCoin(1);
    expect($settings->getAllowedCoins())->toContain(100);
    $settings->removeCoin(1);
    expect($settings->getAllowedCoins())->not()->toContain(100);
});

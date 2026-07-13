<?php

use App\Domain\Vending\Settings;

it('can add and remove drinks', function () {
    $settings = new Settings([], []);
    $settings->addDrink('Tea', 0.70);
    expect($settings->getDrinkPrice('Tea'))->toBe(70);
    $settings->removeDrink('Tea');
    expect($settings->getDrinkPrice('Tea'))->toBeNull();
});

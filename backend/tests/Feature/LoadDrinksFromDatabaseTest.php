<?php

use App\Domain\Vending\VendingMachine;
use App\Models\Drink;
use App\Services\VendingMachineService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads drinks from database', function () {
    Drink::create([
        'name' => 'Tea',
        'price' => 70
    ]);

    $service = app(VendingMachineService::class);

    expect($service->machine()->viewDrinks())->toBeInstanceOf(VendingMachine::class);
});

<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Domain\Vending\VendingMachine;
use App\Domain\Vending\Config\CurrencyConfig;
use App\Domain\Vending\Collections\DrinkCollection;
use App\Domain\Vending\Entities\Drink;
use App\Domain\Vending\Entities\Money;

$config = new CurrencyConfig('лв', '', 'after');
$drinks = new DrinkCollection();
$drinks->add(new Drink('Milk', new Money(50)));
$drinks->add(new Drink('Espresso', new Money(40)));
$drinks->add(new Drink('Long Espresso', new Money(60)));

$machine = new VendingMachine($config, $drinks);

$machine
    ->buyDrink('espresso')
    ->buyDrink('Espresso')
    ->viewDrinks()
    ->putCoin(200)
    ->putCoin(100)
    ->buyDrink('Espresso')
    ->getCoins()
    ->viewAmount()
    ->getCoins();

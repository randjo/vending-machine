<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Vending\VendingMachine;
use App\Models\Currency;
use App\Repositories\DrinkRepository;
use App\Repositories\CoinRepository;

final class VendingMachineService
{
    private VendingMachine $machine;

    public function __construct(
        private DrinkRepository $drinkRepository,
        private CoinRepository $coinRepository,
    ) {
        $this->machine = $this->createMachine();
    }

    private function createMachine(): VendingMachine
    {
        $currencySetting = Currency::first();

        return new VendingMachine(
            [
                'sign' => $currencySetting->sign,
                'space' => $currencySetting->space ?? "",
                'position' => $currencySetting->position,
            ],
            $this->drinkRepository->all(),
            [
                'coins' => $this->coinRepository->all(),
            ],
            VendingMachine::SOURCE_DATABASE
        );
    }

    public function machine(): VendingMachine
    {
        return $this->machine;
    }

    public function reset(): void
    {
        $this->machine->reset();
    }

    public function reload(): void
    {
        $this->machine = $this->createMachine();
    }
}

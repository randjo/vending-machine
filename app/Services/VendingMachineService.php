<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Vending\Currency;
use App\Domain\Vending\VendingMachine;
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
        return new VendingMachine(
            [
                'sign' => 'лв.',
                'space' => '',
                'position' => Currency::CURRENCY_POSITION_AFTER,
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

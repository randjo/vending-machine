<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Vending\CoinData;
use App\DTO\Vending\DrinkData;
use App\Models\Drink;
use App\Models\Coin;
use App\Repositories\DrinkRepository;
use App\Repositories\CoinRepository;

final class AdminVendingService
{
    public function __construct(
        private DrinkRepository $drinkRepository,
        private CoinRepository $coinRepository,
        private VendingMachineService $machineService,
    ) {}


    public function createDrink(DrinkData $data): Drink
    {
        $drink = $this->drinkRepository->create($data->name, $data->price);

        $this->machineService->reload();

        return $drink;
    }


    public function deleteDrink(int $id): int
    {
        $deletedId = $this->drinkRepository->delete($id);
        $this->machineService->reload();

        return $deletedId;
    }

    public function createCoin(CoinData $data): Coin
    {
        $coin = $this->coinRepository->create($data->value);
        $this->machineService->reload();

        return $coin;
    }


    public function deleteCoin(int $id): int
    {
        $deletedId = $this->coinRepository->delete($id);
        $this->machineService->reload();

        return $deletedId;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendingStateResource;
use App\Services\VendingMachineService;
use Illuminate\Http\Request;

final class VendingController extends Controller
{
    public function __construct(private VendingMachineService $service) {}

    public function viewDrinks()
    {
        $drinks = new VendingStateResource($this->service->machine())->drinks();

        return response()->json([
            'drinks' => $drinks
        ]);
    }

    public function putCoin(Request $request)
    {
        $request->validate([
            'coin' => [
                'required',
                'numeric'
            ]
        ]);

        $machine = $this->service->machine();
        $machine->putCoin((float)$request->coin);

        return $machine->display()->latest(1);
    }

    public function coinsList()
    {
        $machine = $this->service->machine();

        $coins = array_map(
            fn(int $coin) => [
                'value' => $coin,
                'formatted_value' => $machine->currency()->format($coin),
            ],
            $machine->coins()
        );

        return response()->json([
            'coins' => $coins
        ]);
    }

    public function buyDrink(Request $request)
    {
        $request->validate([
            'drink' => [
                'required',
                'string'
            ]
        ]);

        $machine = $this->service->machine();
        $machine->buyDrink($request->drink);

        return $machine->display()->latest(1);
    }

    public function display()
    {
        return response()->json([
            'messages' => $this->service
                ->machine()
                ->display()
                ->latest()
        ]);
    }

    public function getCoins()
    {
        $machine = $this->service->machine();
        $machine->getCoins();

        return $machine->display()->latest(1);
    }

    public function viewAmount()
    {
        $machine = $this->service->machine();
        $machine->viewAmount();

        return $machine->display()->latest(1);
    }

    public function restart()
    {
        $this->service->reset();

        return response()->json([
            'message' => 'The machine has been restarted!'
        ]);
    }

    public function state()
    {
        return new VendingStateResource($this->service->machine());
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Vending\VendingMachine;

class VendingStateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var VendingMachine $machine */
        $machine = $this->resource;

        return [
            'drinks' => $this->drinks(),
            'coins' => $this->coins(),
            'balance' => [
                'value' => $machine->balance(),
                'formatted' => $machine->currency()->format($machine->balance()),
            ],
            'display' => $machine->display()->latest(),
        ];
    }

    public function drinks(): array
    {
        /** @var VendingMachine $machine */
        $machine = $this->resource;

        return array_map(
            function ($price, $name) use ($machine) {
                return [
                    'name' => $name,
                    'price' => $price,
                    'formatted_price' => $machine->currency()->format($price),
                ];
            },
            $machine->drinks(),
            array_keys($machine->drinks())
        );
    }

    private function coins(): array
    {
        /** @var VendingMachine $machine */
        $machine = $this->resource;

        return array_map(
            fn(int $coin) => [
                'value' => $coin,
                'formatted_value' => $machine->currency()->format($coin),
            ],
            $machine->coins()
        );
    }
}

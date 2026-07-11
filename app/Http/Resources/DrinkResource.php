<?php

namespace App\Http\Resources;

use App\Domain\Vending\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => number_format(Currency::fromCents($this->price), 2),
        ];
    }
}

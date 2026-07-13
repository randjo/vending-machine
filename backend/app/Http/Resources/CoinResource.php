<?php

namespace App\Http\Resources;

use App\Domain\Vending\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoinResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => number_format(Currency::fromCents($this->value), 2),
        ];
    }
}

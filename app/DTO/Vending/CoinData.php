<?php

declare(strict_types=1);

namespace App\DTO\Vending;

use App\Domain\Vending\Currency;

final readonly class CoinData
{
    public function __construct(public int $value) {}

    public static function fromRequest(array $data): self
    {
        return new self(value: Currency::toCents($data['value']));
    }
}

<?php

declare(strict_types=1);

namespace App\DTO\Vending;

use App\Domain\Vending\Currency;

final readonly class DrinkData
{
    public function __construct(public string $name, public int $price) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            price: Currency::toCents((float) $data['price'])
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Vending\Services;

use App\Domain\Vending\Config\CurrencyConfig;
use App\Domain\Vending\Entities\Money;

final class CurrencyFormatter
{
    public function __construct(
        private CurrencyConfig $config
    ) {}

    public function format(Money $money): string
    {
        $value = number_format($money->amount() / 100, 2);

        if ($this->config->isAfter()) {
            return $value . $this->config->space . $this->config->sign;
        }

        return $this->config->sign . $this->config->space . $value;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class VendingMachine
{
    private Display $display;
    private Settings $settings;
    private Currency $currency;
    private Wallet $wallet;

    public function __construct(
        array $currency,
        array $drinks,
        array $wallet,
    ) {
        $this->display = new Display();

        $this->currency = new Currency(
            sign: $currency['sign'],
            space: $currency['space'],
            position: $currency['position'],
        );

        $this->settings = new Settings(
            $drinks,
            $wallet['coins'],
        );

        $this->wallet = new Wallet($this->settings, $wallet['balance'] ?? 0);
    }

    // 🍹 viewDrinks
    public function viewDrinks(): self
    {
        $this->display->add("Напитки:");

        foreach ($this->settings->getDrinks() as $name => $price) {
            $this->display->add("$name: {$this->currency->format($price)}");
        }

        return $this;
    }

    // 🪙 putCoin
    public function putCoin(float $coin): self
    {
        $coinInCents = (int) ($coin * 100);

        if ($this->wallet->insertCoin($coinInCents)) {
            $this->display->add("Успешно поставихте {$this->currency->format($coinInCents)}, текущата Ви сума е {$this->currency->format($this->wallet->getBalance())}");
        } else {
            $this->display->add(
                "Автоматът приема монети от: " . implode(', ', array_map(
                    fn($coin) => $this->currency->format($coin),
                    $this->settings->getAllowedCoins()
                ))
            );
        }

        return $this;
    }

    // 🥤 buyDrink
    public function buyDrink(string $name): self
    {
        $price = $this->settings->getDrinkPrice($name);

        if ($price === null) {
            $this->display->add("Исканият продукт не е намерен.");
            return $this;
        }

        if ($this->wallet->spend($price)) {
            $this->display->add(
                "Успешно закупихте '{$name}' от " .
                    $this->currency->format($price) .
                    ", текущата Ви сума е " .
                    $this->currency->format($this->wallet->getBalance())
            );
        } else {
            $this->display->add("Недостатъчна наличност.");
        }

        return $this;
    }

    // 💸 getCoins
    public function getCoins(): self
    {
        $balance = $this->wallet->getBalance();
        $change = $this->wallet->getChange();

        if (empty($change)) {
            $this->display->add("Няма ресто за връщане.");
            return $this;
        }

        $parts = [];

        foreach ($change as $coin => $count) {
            $parts[] = "{$count}x{$this->currency->format($coin)}";
        }

        $this->display->add(
            "Получихте ресто " .
                $this->currency->format($balance) .
                " в монети от: " . implode(", ", $parts)
        );

        return $this;
    }

    // 📊 viewAmount
    public function viewAmount(): self
    {
        $this->display->add(
            "Tекущата Ви сума е: " .
                $this->currency->format($this->wallet->getBalance())
        );

        return $this;
    }

    // 📺 output (for final echo)
    public function display(): Display
    {
        return $this->display;
    }
}

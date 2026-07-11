<?php

declare(strict_types=1);

namespace App\Domain\Vending;

use App\Repositories\DatabaseDisplayRepository;
use App\Repositories\DatabaseWalletRepository;

final class VendingMachine
{
    private Display $display;
    private Settings $settings;
    private Currency $currency;
    private Wallet $wallet;
    private bool $lastActionSuccess = false;

    public const SOURCE_CONFIG = 'config';
    public const SOURCE_DATABASE = 'database';

    public function __construct(
        array $currency,
        array $drinksConfig,
        array $walletConfig,
        string $source = self::SOURCE_CONFIG,
    ) {
        $this->initializeCurrency($currency);

        if ($source === self::SOURCE_CONFIG) {
            $this->initializeFromConfig($drinksConfig, $walletConfig);
        } else {
            $this->initializeFromDatabase($drinksConfig, $walletConfig);
        }
    }

    private function initializeCurrency(array $currency)
    {
        $this->currency = new Currency(
            sign: $currency['sign'],
            space: $currency['space'],
            position: $currency['position'],
        );
    }

    private function initializeFromConfig(array $drinksConfig, array $walletConfig): void
    {
        $drinks = [];

        foreach ($drinksConfig as $name => $price) {
            $drinks[$name] = Currency::toCents($price);
        }

        $coins = array_map(
            fn(float $coin) => Currency::toCents($coin),
            $walletConfig['coins']
        );

        $this->display = new Display(new ArrayDisplayRepository());
        $this->settings = new Settings($drinks, $coins);
        $this->wallet = new Wallet($this->settings, new ArrayWalletRepository($walletConfig['balance'] ?? 0));
    }

    private function initializeFromDatabase(array $drinks, array $walletConfig): void
    {
        $this->display = new Display(new DatabaseDisplayRepository());
        $this->settings = new Settings($drinks, $walletConfig['coins']);
        $this->wallet = new Wallet($this->settings, new DatabaseWalletRepository());
    }

    // 🍹 viewDrinks
    public function viewDrinks(): self
    {
        $this->display->add("Напитки:");

        foreach ($this->drinks() as $name => $price) {
            $this->display->add("$name: {$this->currency->format($price)}");
        }

        return $this;
    }

    public function drinks(): array
    {
        return $this->settings->getDrinks();
    }

    // 🪙 putCoin
    public function putCoin(float $coin): self
    {
        $coinInCents = Currency::toCents($coin);

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
            $this->lastActionSuccess = false;
            return $this;
        }

        if ($this->wallet->spend($price)) {
            $this->display->add(
                "Успешно закупихте '{$name}' от " .
                    $this->currency->format($price) .
                    ", текущата Ви сума е " .
                    $this->currency->format($this->wallet->getBalance())
            );
            $this->lastActionSuccess = true;
        } else {
            $this->display->add("Недостатъчна наличност.");
            $this->lastActionSuccess = false;
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

    public function reset(): self
    {
        $this->wallet->reset();
        $this->display->reset();

        return $this;
    }

    public function display(): Display
    {
        return $this->display;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function coins(): array
    {
        return $this->settings->getAllowedCoins();
    }

    public function balance(): int
    {
        return (int) $this->wallet->getBalance();
    }

    public function wasSuccessful(): bool
    {
        return $this->lastActionSuccess;
    }
}

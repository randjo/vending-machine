<?php

declare(strict_types=1);

namespace App\Domain\Vending;

use App\Domain\Vending\Config\CurrencyConfig;
use App\Domain\Vending\Entities\Drink;
use App\Domain\Vending\Entities\Money;
use App\Domain\Vending\Entities\Transaction;
use App\Domain\Vending\Services\ChangeCalculator;
use App\Domain\Vending\Services\CurrencyFormatter;
use App\Domain\Vending\Collections\DrinkCollection;
use App\Domain\Vending\MessageLog;

final class VendingMachine
{
    private Money $balance;

    /** @var int[] */
    private array $insertedCoins = [];

    /** @var Transaction[] */
    private array $transactions = [];
    private CurrencyFormatter $formatter;
    private MessageLog $log;
    private ChangeCalculator $changeCalculator;

    public function __construct(private CurrencyConfig $config, private DrinkCollection $drinks,)
    {
        $this->config->validate();

        $this->formatter = new CurrencyFormatter($this->config);
        $this->changeCalculator = new ChangeCalculator();
        $this->log = new MessageLog();

        if (count($this->drinks->all()) === 0) {
            throw new \InvalidArgumentException("No drinks configured");
        }

        $this->balance = new Money(0);
    }

    /**
     * 🍹 viewDrinks
     */
    public function viewDrinks(): self
    {
        echo "Напитки:\n";

        foreach ($this->drinks->all() as $drink) {
            /** @var Drink $drink */
            echo $drink->name . ': ' . $this->formatter->format($drink->price) . "\n";
        }

        echo "\n";

        return $this;
    }

    /**
     * 🪙 putCoin
     */
    public function putCoin(int $coin): self
    {
        $allowed = [5, 10, 20, 50, 100];

        if (!in_array($coin, $allowed, true)) {
            $this->log->add("Invalid coin: {$coin}");
            echo "Автомата приема монети от: {$this->formatter->format(new Money(5))}, {$this->formatter->format(new Money(10))}, {$this->formatter->format(new Money(20))}, {$this->formatter->format(new Money(50))}, {$this->formatter->format(new Money(100))}\n\n";
            return $this;
        }

        $this->insertedCoins[] = $coin;
        $this->balance = $this->balance->add(new Money($coin));

        $this->log->add("Inserted coin: {$coin}");

        echo "Успешно поставихте " . ($this->formatter->format(new Money($coin))) . ", текущата Ви сума е " . $this->formatter->format($this->balance) . "\n\n";

        return $this;
    }

    /**
     * 🥤 buyDrink (case insensitive)
     */
    public function buyDrink(string $name): self
    {
        $drink = $this->drinks->find($name);

        if (!$drink) {
            $this->log->add("Drink not found: {$name}");
            echo "Исканият продукт не е намерен.\n\n";
            return $this;
        }

        if ($this->balance->isLessThan($drink->price)) {
            $this->log->add("Insufficient funds for {$drink->name}");
            echo "Недостатъчна наличност.\n\n";
            return $this;
        }

        $this->balance = $this->balance->subtract($drink->price);

        $transaction = new Transaction(
            $drink->name,
            $drink->price,
            new \DateTimeImmutable()
        );

        $this->transactions[] = $transaction;

        $this->log->add("Purchased: {$drink->name}");

        echo "Успешно закупихте '{$drink->name}' от " .
            $this->formatter->format($drink->price) .
            ", текущата Ви сума е " .
            $this->formatter->format($this->balance) . "\n\n";

        return $this;
    }

    /**
     * 💸 getCoins (change)
     */
    public function getCoins(): self
    {
        if ($this->balance->amount() === 0) {
            $this->log->add("No change to return");
            echo "Няма ресто за връщане.\n\n";
            return $this;
        }

        $change = $this->changeCalculator->calculate($this->balance->amount());

        echo "Получихте ресто " . $this->formatter->format($this->balance) . " в монети от: ";

        $parts = [];

        foreach ($change as $coin => $count) {
            $parts[] = "{$count}x" . ($this->formatter->format(new Money($coin)));
        }

        echo implode(", ", $parts) . "\n\n";

        $this->log->add("Returned change");

        $this->balance = new Money(0);
        $this->insertedCoins = [];

        return $this;
    }

    /**
     * 📊 viewAmount
     */
    public function viewAmount(): self
    {
        echo "Tекущата Ви сума е " . $this->formatter->format($this->balance) . "\n\n";

        return $this;
    }

    /**
     * 📜 transactions (for future tests / API)
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * 📜 log access (for tests / UI)
     */
    public function getLog(): MessageLog
    {
        return $this->log;
    }
}

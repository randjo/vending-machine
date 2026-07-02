<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Display
{
    /** @var string[] */
    private array $messages = [];

    // 🧾 add message
    public function add(string $message): void
    {
        $this->messages[] = $message;

        // if (count($this->messages) > 3) {
        //     array_shift($this->messages);
        // }
    }

    // 🖨️ render output string
    public function all(): string
    {
        return implode("\n", $this->messages);
    }

    // 🔄 reset display
    public function reset(): void
    {
        $this->messages = [];
    }
}

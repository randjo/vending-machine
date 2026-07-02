<?php

declare(strict_types=1);

namespace App\Domain\Vending;

final class Display
{
    /** @var string[] */
    private array $messages = [];

    public function add(string $message): void
    {
        $this->messages[] = $message;

        // if (count($this->messages) > 3) {
        //     array_shift($this->messages);
        // }
    }

    public function all(): string
    {
        return implode("\n", $this->messages);
    }

    public function reset(): void
    {
        $this->messages = [];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}

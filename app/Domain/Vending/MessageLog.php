<?php

declare(strict_types=1);

namespace App\Domain\Vending;

use App\Domain\Vending\Entities\Message;

final class MessageLog
{
    /** @var Message[] */
    private array $messages = [];

    public function add(string $text): void
    {
        $this->messages[] = new Message(
            $text,
            new \DateTimeImmutable()
        );

        if (count($this->messages) > 3) {
            array_shift($this->messages);
        }
    }

    /** @return Message[] */
    public function all(): array
    {
        return $this->messages;
    }
}

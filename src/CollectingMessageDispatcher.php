<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function array_map;
use function array_push;

class CollectingMessageDispatcher implements MessageDispatcher
{
    /**
     * @var Message[]
     */
    private array $collectedMessages = [];

    public function dispatch(Message ...$messages): void
    {
        array_push($this->collectedMessages, ...$messages);
    }

    /**
     * @return Message[]
     */
    public function collectedMessages(): array
    {
        return $this->collectedMessages;
    }

    /**
     * @return object[]
     */
    public function collectedPayloads(): array
    {
        return array_map(fn (Message $message) => $message->payload(), $this->collectedMessages);
    }
}

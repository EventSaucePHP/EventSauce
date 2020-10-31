<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function array_push;

class CollectingMessageDispatcher implements MessageDispatcher
{
    /**
     * @var Message[]
     */
    private $collectedMessages = [];

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
}

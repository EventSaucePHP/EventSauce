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

    public function dispatch(iterable|Message $messages): void
    {
        $messages = is_iterable($messages) ? $messages : [$messages];

        array_push($this->collectedMessages, ...$messages);
    }

    /**
     * @return Message[]
     */
    public function collectedMessages(): array
    {
        return $this->collectedMessages;
    }

    public function collectedPayloads(): array
    {
        return array_map(fn (Message $message) => $message->event(), $this->collectedMessages);
    }
}

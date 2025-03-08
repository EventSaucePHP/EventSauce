<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class MessageDispatcherChain implements MessageDispatcher
{
    /**
     * @var MessageDispatcher[]
     */
    private array $dispatchers;

    public function __construct(MessageDispatcher ...$dispatchers)
    {
        $this->dispatchers = $dispatchers;
    }

    public function dispatch(iterable|Message $messages): void
    {
        foreach ($this->dispatchers as $dispatcher) {
            $dispatcher->dispatch($messages);
        }
    }
}

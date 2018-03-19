<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class MessageDispatcherChain implements MessageDispatcher
{
    /**
     * @var MessageDispatcher[]
     */
    private $dispatchers;

    /**
     * @param MessageDispatcher[] ...$dispatchers
     */
    public function __construct(MessageDispatcher ...$dispatchers)
    {
        $this->dispatchers = $dispatchers;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(Message ...$messages)
    {
        foreach ($this->dispatchers as $dispatcher) {
            $dispatcher->dispatch(...$messages);
        }
    }
}

<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

final class SynchronousMessageDispatcher implements MessageDispatcher
{
    /**
     * @var Consumer[]
     */
    private $consumers;

    /**
     * @param Consumer[] ...$consumers
     */
    public function __construct(Consumer ...$consumers)
    {
        $this->consumers = $consumers;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(Message ...$messages)
    {
        foreach ($messages as $message) {
            foreach ($this->consumers as $consumer) {
                $consumer->handle($message);
            }
        }
    }
}

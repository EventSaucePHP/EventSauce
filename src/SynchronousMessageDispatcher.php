<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

final class SynchronousMessageDispatcher implements MessageDispatcher
{
    /**
     * @var Consumer[]
     */
    private $consumers;

    public function __construct(Consumer ...$consumers)
    {
        $this->consumers = $consumers;
    }

    public function dispatch(Message ...$messages): void
    {
        foreach ($messages as $message) {
            foreach ($this->consumers as $consumer) {
                $consumer->handle($message);
            }
        }
    }
}

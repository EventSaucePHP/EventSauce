<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

final class SynchronousMessageDispatcher implements MessageDispatcher
{
    /**
     * @var MessageConsumer[]
     */
    private $consumers;

    public function __construct(MessageConsumer ...$consumers)
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

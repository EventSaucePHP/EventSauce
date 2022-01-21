<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageDispatcher;

class ReplayMessageDispatcher implements MessageDispatcherWithBeforeReplay
{
    /**
     * @var list<MessageConsumer>
     */
    private array $consumers;

    public function __construct(protected MessageDispatcher $dispatcher, ReplayableMessageConsumer ...$consumers)
    {
        $this->consumers = $consumers;
    }

    public function beforeReplay(): void
    {
        foreach ($this->consumers as $consumer) {
            $consumer->beforeReplay();
        }
    }

    public function dispatch(Message ...$messages): void
    {
        $this->dispatcher->dispatch(...$messages);
    }
}

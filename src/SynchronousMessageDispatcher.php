<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function is_iterable;

final class SynchronousMessageDispatcher implements MessageDispatcher
{
    /**
     * @var MessageConsumer[]
     */
    private array $consumers;

    public function __construct(MessageConsumer ...$consumers)
    {
        $this->consumers = $consumers;
    }

    public function dispatch(iterable|Message $messages): void
    {
        /** @var iterable<Message> $messages */
        $messages = is_iterable($messages) ? $messages : [$messages];

        foreach ($messages as $message) {
            foreach ($this->consumers as $consumer) {
                $consumer->handle($message);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function is_iterable;

class DecoratingMessageDispatcher implements MessageDispatcher
{
    public function __construct(private MessageDispatcher $dispatcher, private MessageDecorator $decorator)
    {
    }

    public function dispatch(iterable|Message $messages): void
    {
        $messages = is_iterable($messages) ? $messages : [$messages];

        $this->dispatcher->dispatch(
            array_map(fn (Message $message) => $this->decorator->decorate($message), (array) $messages)
        );
    }
}

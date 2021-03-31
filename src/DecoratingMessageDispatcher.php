<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class DecoratingMessageDispatcher implements MessageDispatcher
{
    public function __construct(private MessageDispatcher $dispatcher, private MessageDecorator $decorator)
    {
    }

    public function dispatch(Message ...$messages): void
    {
        $messages = array_map(fn (Message $message) => $this->decorator->decorate($message), $messages);
        $this->dispatcher->dispatch(...$messages);
    }
}

<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class MessageDispatchingEventDispatcher implements EventDispatcher
{
    private MessageDispatcher $dispatcher;
    private MessageDecorator $decorator;

    public function __construct(MessageDispatcher $dispatcher, ?MessageDecorator $decorator = null)
    {
        $this->dispatcher = $dispatcher;
        $this->decorator = $decorator ?: new DefaultHeadersDecorator();
    }

    public function dispatch(iterable|object $events): void
    {
        $this->dispatchWithHeaders([], $events);
    }

    public function dispatchWithHeaders(array $headers, iterable|object $events): void
    {
        $events = is_iterable($events) ? $events : [$events];
        $messages = [];

        foreach ($events as $event) {
            $messages[] = $this->decorator->decorate(new Message($event, $headers));
        }

        $this->dispatcher->dispatch($messages);
    }
}

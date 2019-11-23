<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class MessageDispatchingEventDispatcher implements EventDispatcher
{
    /**
     * @var MessageDispatcher
     */
    private $dispatcher;

    /**
     * @var MessageDecorator
     */
    private $decorator;

    public function __construct(MessageDispatcher $dispatcher, ?MessageDecorator $decorator = null)
    {
        $this->dispatcher = $dispatcher;
        $this->decorator = $decorator ?: new DefaultHeadersDecorator();
    }

    public function dispatch(object ...$events): void
    {
        $this->dispatchWithHeaders([], ...$events);
    }

    public function dispatchWithHeaders(array $headers, object ...$events): void
    {
        $messages = [];

        foreach ($events as $event) {
            $messages[] = $this->decorator->decorate(new Message($event, $headers));
        }

        $this->dispatcher->dispatch(...$messages);
    }
}

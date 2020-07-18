<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class DecoratingMessageDispatcher implements MessageDispatcher
{
    /**
     * @var MessageDispatcher
     */
    private $dispatcher;

    /**
     * @var MessageDecorator
     */
    private $decorator;

    public function __construct(MessageDispatcher $dispatcher, MessageDecorator $decorator = null)
    {
        $this->dispatcher = $dispatcher;
        $this->decorator = $decorator ?: new DefaultHeadersDecorator();
    }

    public function dispatch(Message ...$messages): void
    {
        $messages = array_map(
            function ($message) {
                return $this->decorator->decorate($message);
            },
            $messages
        );

        $this->dispatcher->dispatch(...$messages);
    }
}

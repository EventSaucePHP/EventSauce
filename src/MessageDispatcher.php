<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    /**
     * @throws UnableToDispatchMessages
     * @param iterable<Message>|Message $messages
     */
    public function dispatch(iterable|Message $messages): void;
}

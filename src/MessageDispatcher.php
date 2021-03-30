<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    /**
     * @throws UnableToDispatchMessages
     */
    public function dispatch(Message ...$messages): void;
}

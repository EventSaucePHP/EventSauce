<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    /**
     * @param Message[] ...$messages
     */
    public function dispatch(Message ...$messages);
}

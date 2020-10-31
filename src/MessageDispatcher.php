<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    /**
     * @return void
     */
    public function dispatch(Message ...$messages);
}

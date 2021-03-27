<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    public function dispatch(Message ...$messages): void;
}

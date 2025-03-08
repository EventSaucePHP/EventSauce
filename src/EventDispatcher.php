<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface EventDispatcher
{
    /**
     * @param iterable<object>|object $events
     */
    public function dispatch(iterable|object $events): void;

    /**
     * @param iterable<object>|object $events
     */
    public function dispatchWithHeaders(array $headers, iterable|object $events): void;
}

<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface EventDispatcher
{
    public function dispatch(object ...$events): void;

    public function dispatchWithHeaders(array $headers, object ...$events): void;
}

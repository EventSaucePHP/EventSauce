<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

abstract class EventConsumer implements MessageConsumer
{
    public function handle(Message $message): void
    {
        $event = $message->event();
        $parts = explode('\\', get_class($event));
        $method = 'handle' . end($parts);

        if (method_exists($this, $method)) {
            $this->{$method}($event, $message);
        }
    }
}

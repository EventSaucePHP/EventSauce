<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function end;
use function explode;
use function get_class;
use function method_exists;

class EventHandlingMessageConsumer implements MessageConsumer
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

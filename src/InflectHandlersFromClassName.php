<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class InflectHandlersFromClassName implements HandleInflector
{
    public function getMethodNames(object $consumer, Message $message): array
    {
        $event = $message->payload();
        $parts = explode('\\', get_class($event));

        return ['handle' . end($parts)];
    }
}

<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\EventConsumption;

use EventSauce\EventSourcing\Message;

use function get_class;
use function strrpos;
use function substr;

class InflectHandlerMethodsFromClassName implements HandleMethodInflector
{
    public function handleMethods(object $consumer, Message $message): array
    {
        $event = $message->payload();
        $className = get_class($event);

        return ['handle' . substr($className, (strrpos($className, '\\') ?: -1) + 1)];
    }
}

<?php

namespace EventSauce\EventSourcing;

use function get_class;

class DotSeparatedSnakeCaseInflector implements EventNameInflector
{
    public function classNameToEventName(string $className): string
    {
        return str_replace('\\_', '.', strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $className)));
    }

    public function eventNameToClassName(string $eventName): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '\\ ', $eventName))));
    }

    public function eventToEventName(Event $event): string
    {
        return $this->classNameToEventName(get_class($event));
    }
}

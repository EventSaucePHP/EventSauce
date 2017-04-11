<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\Event;

/**
 * The EventName represents the name of the event and allows itself to be represented as a class name or
 * a lowercase/underscore/dot-notated string.
 *
 * Examples:
 *      EventName::fromClassName('Some\ClassName')->toEventName(); // some.class_name
 *      EventName::fromEventName('some.class_name')->toEventName(); // Some\ClassName
 *      EventName::fromEvent($event)->toEventName(); // another_example_of.some_event
 */
final class EventType
{
    private $className;

    private function __construct(string $className)
    {
        $this->className = $className;
    }

    public static function fromClassName(string $className): EventType
    {
        return new EventType($className);
    }

    public static function fromEventType(string $eventName)
    {
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '\\ ', $eventName))));

        return new EventType($className);
    }

    public static function fromEvent(Event $event): EventType
    {
        return new EventType(get_class($event));
    }

    public function toClassName(): string
    {
        return $this->className;
    }

    public function toEventName(): string
    {
        return str_replace('\\_', '.', strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $this->className)));
    }
}
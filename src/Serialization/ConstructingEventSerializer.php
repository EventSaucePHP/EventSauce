<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

class ConstructingEventSerializer implements EventSerializer
{
    /**
     * @param SerializableEvent $event
     *
     * @return array
     */
    public function serializeEvent(object $event): array
    {
        if (!$event instanceof SerializableEvent) {
            throw new \InvalidArgumentException(
                'Cannot serialize event that does not implement "EventSauce\EventSourcing\Serialization\SerializableEvent".'
            );
        }

        return $event->toPayload();
    }

    public function unserializePayload(string $className, array $payload): object
    {
        if (!is_subclass_of($className, SerializableEvent::class)) {
            throw new \InvalidArgumentException(
                'Cannot unserialize payload into an event that does not implement "EventSauce\EventSourcing\Serialization\SerializableEvent".'
            );
        }

        /* @var SerializableEvent $className */
        return $className::fromPayload($payload);
    }
}

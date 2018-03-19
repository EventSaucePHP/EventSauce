<?php

namespace EventSauce\EventSourcing\Serialization;

class ConstructingEventSerializer implements EventSerializer
{
    public function serializeEvent(object $event): array
    {
        /** @var $event SerializableEvent */
        return $event->toPayload();
    }

    public function unserializePayload(string $className, array $payload): object
    {
        /** @var $className SerializableEvent */
        return $className::fromPayload($payload);
    }
}
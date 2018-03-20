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
        return $event->toPayload();
    }

    public function unserializePayload(string $className, array $payload): object
    {
        /* @var SerializableEvent $className */
        return $className::fromPayload($payload);
    }
}

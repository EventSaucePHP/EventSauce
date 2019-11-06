<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

class ConstructingPayloadSerializer implements PayloadSerializer
{
    /**
     * @param SerializablePayload $event
     */
    public function serializePayload(object $event): array
    {
        return $event->toPayload();
    }

    public function unserializePayload(string $className, array $payload): object
    {
        /* @var SerializablePayload $className */
        return $className::fromPayload($payload);
    }
}

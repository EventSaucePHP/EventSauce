<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use function assert;

class ConstructingPayloadSerializer implements PayloadSerializer
{
    public function serializePayload(object $event): array
    {
        assert($event instanceof SerializablePayload);

        return $event->toPayload();
    }

    public function unserializePayload(string $className, array $payload): object
    {
        /* @var SerializablePayload $className */
        // @phpstan-ignore-next-line
        return $className::fromPayload($payload);
    }
}

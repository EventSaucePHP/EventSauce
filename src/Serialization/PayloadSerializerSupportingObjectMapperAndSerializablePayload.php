<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use function is_a;

class PayloadSerializerSupportingObjectMapperAndSerializablePayload implements PayloadSerializer
{
    private ConstructingPayloadSerializer $constructingSerializer;
    private ObjectMapperPayloadSerializer $objectMapperSerializer;

    public function __construct(
        ConstructingPayloadSerializer $constructingSerializer = null,
        ObjectMapperPayloadSerializer $objectMapperSerializer = null,
    ) {
        $this->constructingSerializer = $constructingSerializer ?? new ConstructingPayloadSerializer();
        $this->objectMapperSerializer = $objectMapperSerializer ?? new ObjectMapperPayloadSerializer();
    }

    public function serializePayload(object $event): array
    {
        if ($event instanceof SerializablePayload) {
            return $this->constructingSerializer->serializePayload($event);
        }

        return $this->objectMapperSerializer->serializePayload($event);
    }

    public function unserializePayload(string $className, array $payload): object
    {
        if (is_a($className, SerializablePayload::class, true)) {
            // @phpstan-ignore-next-line
            return $this->constructingSerializer->unserializePayload($className, $payload);
        }

        return $this->objectMapperSerializer->unserializePayload($className, $payload);
    }
}

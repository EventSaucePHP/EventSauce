<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\ObjectHydrator\ObjectMapper;
use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;

class ObjectMapperPayloadSerializer implements PayloadSerializer
{
    private ObjectMapper $objectMapper;

    public function __construct(
        ObjectMapper $objectMapper = null
    ) {
        $this->objectMapper = $objectMapper ?: new ObjectMapperUsingReflection();
    }

    public function serializePayload(object $event): array
    {
        return $this->objectMapper->serializeObject($event);
    }

    public function unserializePayload(string $className, array $payload): object
    {
        /** @var class-string $className */
        return $this->objectMapper->hydrateObject($className, $payload);
    }
}

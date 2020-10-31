<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

final class TypeValidatingPayloadSerializer implements PayloadSerializer
{
    /**
     * @var PayloadSerializer
     */
    private $serializer;

    /**
     * @var string
     */
    private $eventClassName;

    public function __construct(
        PayloadSerializer $serializer,
        string $eventClassName
    ) {
        $this->serializer = $serializer;
        $this->eventClassName = $eventClassName;
    }

    public function serializePayload(object $event): array
    {
        if ( ! $event instanceof $this->eventClassName) {
            throw new \InvalidArgumentException(sprintf('Cannot serialize event that does not implement "%s".', $this->eventClassName));
        }

        return $this->serializer->serializePayload($event);
    }

    public function unserializePayload(string $className, array $payload): object
    {
        if ( ! is_subclass_of($className, $this->eventClassName)) {
            throw new \InvalidArgumentException(sprintf('Cannot unserialize payload into an event that does not implement "%s".', $this->eventClassName));
        }

        return $this->serializer->unserializePayload($className, $payload);
    }
}

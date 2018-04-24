<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

final class TypeValidatingEventSerializer implements EventSerializer
{
    private $serializer;
    private $eventClassName;

    public function __construct(
        EventSerializer $serializer,
        string $eventClassName
    ) {
        $this->serializer = $serializer;
        $this->eventClassName = $eventClassName;
    }

    public function serializeEvent(object $event): array
    {
        if ( ! $event instanceof $this->eventClassName) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot serialize event that does not implement "%s".',
                $this->eventClassName
            ));
        }

        return $this->serializer->serializeEvent($event);
    }

    public function unserializePayload(string $className, array $payload): object
    {
        if ( ! is_subclass_of($className, $this->eventClassName)) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot unserialize payload into an event that does not implement "%s".',
                $this->eventClassName
            ));
        }

        return $this->serializer->unserializePayload($className, $payload);
    }
}

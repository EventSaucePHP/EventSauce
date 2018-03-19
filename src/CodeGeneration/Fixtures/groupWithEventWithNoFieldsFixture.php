<?php

namespace Without\Fields;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class WithoutFields implements SerializableEvent
{
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new WithoutFields();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): WithoutFields
    {
        return new WithoutFields();
    }
}

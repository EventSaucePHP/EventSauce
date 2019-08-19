<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class AggregateWasInitiated implements SerializablePayload
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new static();
    }
}

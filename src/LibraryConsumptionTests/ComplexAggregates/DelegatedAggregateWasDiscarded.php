<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class DelegatedAggregateWasDiscarded implements SerializablePayload
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): static
    {
        return new DelegatedAggregateWasDiscarded();
    }
}

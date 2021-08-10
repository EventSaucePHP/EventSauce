<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class DelegatedActionWasPerformed implements SerializablePayload
{
    public function __construct(private int $counter)
    {
    }

    public function toPayload(): array
    {
        return ['counter' => $this->counter];
    }

    public static function fromPayload(array $payload): static
    {
        return new DelegatedActionWasPerformed($payload['counter']);
    }

    public function counter(): int
    {
        return $this->counter;
    }
}

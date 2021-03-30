<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class DelegatedActionWasPerformed implements SerializablePayload
{
    /**
     * @var int
     */
    private $counter;

    public function __construct(int $counter)
    {
        $this->counter = $counter;
    }

    public function toPayload(): array
    {
        return ['counter' => $this->counter];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new DelegatedActionWasPerformed($payload['counter']);
    }

    public function counter(): int
    {
        return $this->counter;
    }
}

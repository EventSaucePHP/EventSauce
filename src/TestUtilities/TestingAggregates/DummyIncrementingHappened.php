<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

/**
 * @codeCoverageIgnore
 * @testAsset
 */
class DummyIncrementingHappened implements SerializablePayload
{
    private int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function toPayload(): array
    {
        return ['number' => $this->number];
    }

    public static function fromPayload(array $payload): self
    {
        return new DummyIncrementingHappened($payload['number']);
    }

    public function number(): int
    {
        return $this->number;
    }
}

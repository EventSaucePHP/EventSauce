<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

/**
 * @codeCoverageIgnore
 */
class DummyIncrementingHappened implements SerializableEvent
{
    /**
     * @var int
     */
    private $number = 0;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function toPayload(): array
    {
        return ['number' => $this->number];
    }

    public static function fromPayload(array $payload): SerializableEvent
    {
        return new DummyIncrementingHappened($payload['number']);
    }

    public function number(): int
    {
        return $this->number;
    }
}

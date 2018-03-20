<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

/**
 * @codeCoverageIgnore
 */
class DummyTaskWasExecuted implements SerializableEvent
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): SerializableEvent
    {
        return new DummyTaskWasExecuted();
    }
}

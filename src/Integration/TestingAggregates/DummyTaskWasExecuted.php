<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

/**
 * @codeCoverageIgnore
 */
class DummyTaskWasExecuted implements Event
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): Event
    {
        return new DummyTaskWasExecuted();
    }
}
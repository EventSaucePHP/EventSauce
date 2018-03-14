<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Event;

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

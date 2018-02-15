<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

/**
 * @codeCoverageIgnore
 */
class DummyTaskWasExecuted implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording)
    {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function eventVersion(): int
    {
        return 1;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload, AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): Event
    {
        return new DummyTaskWasExecuted($aggregateRootId, $timeOfRecording);
    }
}
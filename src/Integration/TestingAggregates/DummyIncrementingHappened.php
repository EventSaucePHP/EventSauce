<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

/**
 * @codeCoverageIgnore
 */
class DummyIncrementingHappened implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    /**
     * @var int
     */
    private $number;

    public function __construct(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording, int $number)
    {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->number = $number;
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
        return new DummyIncrementingHappened($aggregateRootId, $timeOfRecording, $payload['number']);
    }

    public function number(): int
    {
        return $this->number;
    }
}
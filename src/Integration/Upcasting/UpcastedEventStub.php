<?php

namespace EventSauce\EventSourcing\Integration\Upcasting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\UuidAggregateRootId;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

class UpcastedEventStub implements Event
{
    /**
     * @var UuidAggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    /**
     * @var string
     */
    private $property;

    public function __construct(UuidAggregateRootId $aggregateRootId, PointInTime $timeOfRecording, string $property)
    {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->property = $property;
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
        return ['property' => $this->property];
    }

    public static function fromPayload(array $payload, AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): Event
    {
        return new UpcastedEventStub($aggregateRootId, $timeOfRecording, $payload['property'] ?? 'undefined');
    }
}
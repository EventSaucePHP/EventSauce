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
     * @var PointInTime
     */
    private $timeOfRecording;

    /**
     * @var int
     */
    private $number;

    public function __construct(PointInTime $timeOfRecording, int $number)
    {
        $this->timeOfRecording = $timeOfRecording;
        $this->number = $number;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload, PointInTime $timeOfRecording): Event
    {
        return new DummyIncrementingHappened($timeOfRecording, $payload['number']);
    }

    public function number(): int
    {
        return $this->number;
    }
}
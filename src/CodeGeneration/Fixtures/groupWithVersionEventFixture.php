<?php

namespace With\Versioned\Event;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class VersionTwo implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording): Event
    {
        return new VersionTwo(
            $aggregateRootId,
            $timeOfRecording
        );
    }

    public function toPayload(): array
    {
        return [
            '__event_version' => 2,
        ];
    }

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): VersionTwo
    {
        return new VersionTwo(
            $aggregateRootId,
            $timeOfRecording
        );
    }

}

<?php

namespace With\EventFieldSerialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class EventName implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $title
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->title = $title;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function title(): string
    {
        return $this->title;
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
        return new EventName(
            $aggregateRootId,
            $timeOfRecording,
            strtolower($payload['title'])
        );
    }

    public function toPayload(): array
    {
        return [
            'title' => strtoupper($this->title),
            '__event_version' => 1,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withTitle(string $title): EventName
    {
        $this->title = $title;

        return $this;
    }

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): EventName
    {
        return new EventName(
            $aggregateRootId,
            $timeOfRecording,
            strtolower('Title')
        );
    }

}

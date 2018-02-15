<?php

namespace Group\With\Defaults;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class EventWithDescription implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $description
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->description = $description;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function description(): string
    {
        return $this->description;
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
        return new EventWithDescription(
            $aggregateRootId,
            $timeOfRecording,
            (string) $payload['description']
        );
    }

    public function toPayload(): array
    {
        return [
            'description' => (string) $this->description,
            '__event_version' => 1,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withDescription(string $description): EventWithDescription
    {
        $this->description = $description;

        return $this;
    }

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): EventWithDescription
    {
        return new EventWithDescription(
            $aggregateRootId,
            $timeOfRecording,
            (string) 'This is a description.'
        );
    }

}

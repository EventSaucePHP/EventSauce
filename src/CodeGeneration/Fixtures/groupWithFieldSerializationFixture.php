<?php

namespace Group\With\FieldDeserialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class WithFieldSerializers implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var array
     */
    private $items;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        array $items
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->items = $items;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function eventVersion(): int
    {
        return 1;
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
        return new WithFieldSerializers(
            $aggregateRootId,
            $timeOfRecording,
            array_map(function ($property) {
                return ['property' => $property];
            }, $payload['items'])
        );
    }

    public function toPayload(): array
    {
        return [
            'items' => array_map(function ($item) {
                return $item['property'];
            }, $this->items)
        ];
    }

    public static function withItems(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording, array $items): WithFieldSerializers
    {
        return new WithFieldSerializers(
            $aggregateRootId,
            $timeOfRecording,
            $items
        );
    }

}

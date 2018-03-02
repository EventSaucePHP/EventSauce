<?php

namespace Group\With\FieldDeserialization;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class WithFieldSerializers implements Event
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording,
        array $items
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->items = $items;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new WithFieldSerializers(
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
            }, $this->items),
        ];
    }

    public static function withItems(PointInTime $timeOfRecording, array $items): WithFieldSerializers
    {
        return new WithFieldSerializers(
            $timeOfRecording,
            $items
        );
    }

}

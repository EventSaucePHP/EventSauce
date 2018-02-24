<?php

namespace EventSauce\EventSourcing\Integration\Upcasting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

class UpcastedEventStub implements Event
{
    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    /**
     * @var string
     */
    private $property;

    public function __construct(PointInTime $timeOfRecording, string $property)
    {
        $this->timeOfRecording = $timeOfRecording;
        $this->property = $property;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public function toPayload(): array
    {
        return ['property' => $this->property, self::EVENT_VERSION_PAYLOAD_KEY => 1];
    }

    public static function fromPayload(array $payload, PointInTime $timeOfRecording): Event
    {
        return new UpcastedEventStub($timeOfRecording, $payload['property'] ?? 'undefined');
    }
}
<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

class EventStub implements Event
{
    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    /**
     * @var string
     */
    private $value;

    public function __construct(PointInTime $timeOfRecording, string $value)
    {
        $this->timeOfRecording = $timeOfRecording;
        $this->value = $value;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public function toPayload(): array
    {
        return ['__event_version' => 2, 'value' => $this->value];
    }

    public static function fromPayload(array $payload, PointInTime $timeOfRecording): Event
    {
        return new EventStub($timeOfRecording, $payload['value'] ?? 'default');
    }
}
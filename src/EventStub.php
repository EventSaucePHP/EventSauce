<?php

namespace EventSauce\EventSourcing;

use function compact;
use EventSauce\EventSourcing\Time\TestClock;

class EventStub implements Event
{
    /**
     * @var PointInTime
     */
    private $pointInTime;

    /**
     * @var string
     */
    private $value;

    public function __construct(PointInTime $pointInTime, string $value)
    {
        $this->pointInTime = $pointInTime;
        $this->value = $value;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->pointInTime;
    }

    public function toPayload(): array
    {
        return ['value' => $this->value];
    }

    public static function fromPayload(array $payload, PointInTime $timeOfRecording): Event
    {
        return new static($timeOfRecording, $payload['value']);
    }

    public static function create(string $value = null)
    {
        return static::fromPayload(compact('value'), (new TestClock())->pointInTime());
    }
}
<?php

namespace With\Versioned\Event;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class VersionTwo implements Event
{
    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording
    ) {
        $this->timeOfRecording = $timeOfRecording;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new VersionTwo(
            $timeOfRecording
        );
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function with(PointInTime $timeOfRecording): VersionTwo
    {
        return new VersionTwo(
            $timeOfRecording
        );
    }

}

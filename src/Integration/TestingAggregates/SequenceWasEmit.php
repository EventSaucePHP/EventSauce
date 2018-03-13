<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

/**
 * @codeCoverageIgnore
 */
class SequenceWasEmit implements Event
{
    /**
     * @var PointInTime
     */
    private $pointInTime;

    /**
     * @var int
     */
    private $version;

    /**
     * SequenceWasEmit constructor.
     *
     * @param \EventSauce\EventSourcing\PointInTime $pointInTime
     * @param int                                   $int
     */
    public function __construct(PointInTime $pointInTime, int $version)
    {
        $this->pointInTime = $pointInTime;
        $this->version = $version;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->pointInTime;
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload, PointInTime $timeOfRecording): Event
    {

    }
}
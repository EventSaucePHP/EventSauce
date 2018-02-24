<?php

namespace With\Commands;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class DoSomething
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var string
     */
    private $reason;

    public function __construct(
        PointInTime $timeOfRequest,
        string $reason
    ) {
        $this->timeOfRequest = $timeOfRequest;
        $this->reason = $reason;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function reason(): string
    {
        return $this->reason;
    }

}

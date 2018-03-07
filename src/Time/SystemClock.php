<?php

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use EventSauce\EventSourcing\PointInTime;

class SystemClock implements Clock
{
    public function dateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function pointInTime(): PointInTime
    {
        return PointInTime::fromDateTime($this->dateTime());
    }
}
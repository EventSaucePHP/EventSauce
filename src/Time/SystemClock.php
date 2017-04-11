<?php

namespace EventSauce\Time;

use DateTimeImmutable;
use EventSauce\EventSourcing\PointInTime;

class SystemClock implements Clock
{
    public function dateTime(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('U.u', sprintf('%.6f', microtime(true)));
    }

    public function pointInTime(): PointInTime
    {
        return PointInTime::fromDateTime($this->dateTime());
    }
}
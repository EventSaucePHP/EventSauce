<?php

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use EventSauce\EventSourcing\PointInTime;

interface Clock
{
    public function dateTime(): DateTimeImmutable;

    public function pointInTime(): PointInTime;
}
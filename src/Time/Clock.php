<?php

namespace EventSauce\Time;

use DateTimeImmutable;
use EventSauce\EventSourcing\PointInTime;

interface Clock
{
    public function dateTime(): DateTimeImmutable;

    public function pointInTime(): PointInTime;
}
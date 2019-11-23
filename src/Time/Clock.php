<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use DateTimeZone;
use EventSauce\EventSourcing\PointInTime;

interface Clock
{
    public function dateTime(): DateTimeImmutable;

    public function pointInTime(): PointInTime;

    public function timeZone(): DateTimeZone;
}

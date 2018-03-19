<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use EventSauce\EventSourcing\PointInTime;

interface Clock
{
    /**
     * @return DateTimeImmutable
     */
    public function dateTime(): DateTimeImmutable;

    /**
     * @return PointInTime
     */
    public function pointInTime(): PointInTime;
}

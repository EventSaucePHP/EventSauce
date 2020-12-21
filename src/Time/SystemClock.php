<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use DateTimeZone;

class SystemClock implements Clock
{
    /**
     * @var DateTimeZone
     */
    private $timeZone;

    public function __construct(DateTimeZone $timeZone = null)
    {
        $this->timeZone = $timeZone ?: new DateTimeZone('UTC');
    }

    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timeZone);
    }

    public function timeZone(): DateTimeZone
    {
        return $this->timeZone;
    }
}

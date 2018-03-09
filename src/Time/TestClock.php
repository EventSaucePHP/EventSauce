<?php

namespace EventSauce\EventSourcing\Time;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use EventSauce\EventSourcing\PointInTime;
use function strtotime;

class TestClock implements Clock
{
    /**
     * @private
     */
    const FORMAT_OF_TIME = 'Y-m-d H:i:s.uO';

    /**
     * @var DateTimeImmutable
     */
    private $time;

    /**
     * @var DateTimeZone
     */
    private $timeZone;

    public function __construct(DateTimeZone $timeZone = null)
    {
        $this->timeZone = $timeZone ?: new DateTimeZone('UTC');
        $this->tick();
    }

    public function tick()
    {
        $this->time = new DateTimeImmutable('now', $this->timeZone);
    }

    public function fixate(string $dateTime)
    {
        $preciseTime = sprintf('%s.000000', $dateTime);
        $this->time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $preciseTime, $this->timeZone);
    }

    public function dateTime(): DateTimeImmutable
    {
        return $this->time;
    }

    public function pointInTime(): PointInTime
    {
        return PointInTime::fromDateTime($this->dateTime());
    }
}
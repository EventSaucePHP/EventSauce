<?php

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use EventSauce\EventSourcing\PointInTime;

class TestClock implements Clock
{
    /**
     * @private
     */
    const FORMAT_OF_TIME = 'Y-m-d H:i:s.uO';

    private $time;

    final public function __construct()
    {
        $this->tick();
    }

    public function tick()
    {
        $this->time = DateTimeImmutable::createFromFormat('U.u', sprintf('%.6f', microtime(true)));
    }

    public function fixate(string $dateTime)
    {
        $preciseTime = sprintf('%s.000000+0000', $dateTime);
        $this->time = DateTimeImmutable::createFromFormat(self::FORMAT_OF_TIME, $preciseTime);
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
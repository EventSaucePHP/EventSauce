<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use DateTimeZone;
use EventSauce\EventSourcing\PointInTime;

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

    /**
     * @param DateTimeZone|null $timeZone
     */
    public function __construct(DateTimeZone $timeZone = null)
    {
        $this->timeZone = $timeZone ?: new DateTimeZone('UTC');
        $this->tick();
    }

    public function tick()
    {
        $this->time = new DateTimeImmutable('now', $this->timeZone);
    }

    /**
     * @param string $dateTime
     */
    public function fixate(string $dateTime)
    {
        $preciseTime = sprintf('%s.000000', $dateTime);
        $this->time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $preciseTime, $this->timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function dateTime(): DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * {@inheritdoc}
     */
    public function pointInTime(): PointInTime
    {
        return PointInTime::fromDateTime($this->dateTime());
    }
}

<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use DateTimeZone;
use EventSauce\EventSourcing\PointInTime;
use InvalidArgumentException;

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

    public function fixate(string $input)
    {
        $preciseTime = sprintf('%s.000000', $input);
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $preciseTime, $this->timeZone);

        if ( ! $dateTime instanceof DateTimeImmutable) {
            throw new InvalidArgumentException("Invalid input for date/time fixation provided: {$input}");
        }

        $this->time = $dateTime;
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

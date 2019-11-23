<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeZone;
use EventSauce\EventSourcing\PointInTime;
use PHPUnit\Framework\TestCase;

class SystemClockTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_very_precise_date_time_immutables(): void
    {
        $clock = new SystemClock();
        $d1 = $clock->dateTime();
        $d2 = $clock->dateTime();
        $this->assertTrue($d1 < $d2);
    }

    /**
     * @test
     */
    public function creating_points_in_time(): void
    {
        $clock = new SystemClock();
        $pointInTime = $clock->pointInTime();
        $this->assertInstanceOf(PointInTime::class, $pointInTime);
    }

    /**
     * @test
     */
    public function timezone_defaults_to_utc()
    {
        $clock = new SystemClock();
        $this->assertEquals('UTC', $clock->timeZone()->getName());
    }

    /**
     * @test
     */
    public function setting_a_timezone_explicitly()
    {
        $clock = new SystemClock(new DateTimeZone('Europe/Amsterdam'));
        $this->assertEquals('Europe/Amsterdam', $clock->timeZone()->getName());
    }
}

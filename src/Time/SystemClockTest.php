<?php

namespace EventSauce\Time;

use EventSauce\EventSourcing\PointInTime;
use PHPUnit\Framework\TestCase;

class SystemClockTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_very_precise_date_time_immutables()
    {
        $clock = new SystemClock();
        $d1 = $clock->dateTime();
        $d2 = $clock->dateTime();
        $this->assertTrue($d1 < $d2);
    }

    /**
     * @test
     */
    public function creating_points_in_time()
    {
        $clock = new SystemClock();
        $pointInTime = $clock->pointInTime();
        $this->assertInstanceOf(PointInTime::class, $pointInTime);
    }
}
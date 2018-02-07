<?php

namespace EventSauce\EventSourcing\Time;

use EventSauce\EventSourcing\PointInTime;
use PHPUnit\Framework\TestCase;

class TestClockTest extends TestCase
{
    /**
     * @test
     */
    public function getting_back_equal_date_times()
    {
        $clock = new TestClock();
        $d1 = $clock->dateTime();
        $d2 = $clock->dateTime();
        $this->assertEquals($d1, $d2);
    }

    /**
     * @test
     */
    public function ticking_the_clock_sets_it_forward()
    {
        $clock = new TestClock();
        $d1 = $clock->dateTime();
        $clock->tick();
        $d2 = $clock->dateTime();
        $this->assertNotEquals($d1, $d2);
        $this->assertTrue($d1 < $d2);
    }

    /**
     * @test
     */
    public function fixating_the_clock()
    {
        $clock = new TestClock();
        $clock->fixate('2017-01-01 12:00:00');
        $d1 = $clock->dateTime();
        $clock->fixate('2016-01-01 12:00:00');
        $d2 = $clock->dateTime();
        $this->assertTrue($d1 > $d2);
    }

    /**
     * @test
     */
    public function creating_points_in_time()
    {
        $clock = new TestClock();
        $pointInTime = $clock->pointInTime();
        $this->assertInstanceOf(PointInTime::class, $pointInTime);
    }
}
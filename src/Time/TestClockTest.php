<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateInterval;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TestClockTest extends TestCase
{
    /**
     * @test
     */
    public function getting_back_equal_date_times(): void
    {
        $clock = new TestClock();
        $d1 = $clock->currentTime();
        $d2 = $clock->currentTime();
        $this->assertEquals($d1, $d2);
    }

    /**
     * @test
     */
    public function moving_the_clock_forward(): void
    {
        $clock = new TestClock();
        $clock->fixate('2000-01-01 10:00:00');
        $interval = new DateInterval('PT2H');
        $d1 = $clock->currentTime();
        $clock->moveForward($interval);
        $d2 = $clock->currentTime();
        $diff = $d1->diff($d2);
        $this->assertEquals('2000-01-01 12:00:00', $d2->format('Y-m-d H:i:s'));
        $this->assertTrue($d1 < $d2);
        $this->assertEquals($interval, $diff);
    }

    /**
     * @test
     */
    public function it_exposes_a_timezone(): void
    {
        $clock = new TestClock();
        $this->assertEquals(new DateTimeZone('UTC'), $clock->timeZone());

        $clock = new TestClock(new DateTimeZone('Europe/Amsterdam'));
        $this->assertEquals(new DateTimeZone('Europe/Amsterdam'), $clock->timeZone());
    }

    /**
     * @test
     */
    public function ticking_the_clock_sets_it_forward(): void
    {
        $clock = new TestClock();
        $d1 = $clock->currentTime();
        $clock->tick();
        $d2 = $clock->currentTime();
        $this->assertNotEquals($d1, $d2);
        $this->assertTrue($d1 < $d2);
    }

    /**
     * @test
     */
    public function fixating_the_clock(): void
    {
        $clock = new TestClock();
        $clock->fixate('2017-01-01 12:00:00');
        $d1 = $clock->currentTime();
        $clock->fixate('2016-01-01 12:00:00');
        $d2 = $clock->currentTime();
        $this->assertTrue($d1 > $d2);
    }

    /**
     * @test
     */
    public function failing_to_fixate_the_clock(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $clock = new TestClock();
        $clock->fixate('sihvwshv oihacih ohaciohc');
    }
}

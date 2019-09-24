<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PointInTimeTest extends TestCase
{
    /**
     * @test
     */
    public function creating_from_string(): void
    {
        $pointInTime = PointInTime::fromString('2017-01-01 10:30:00.000000+0000');
        $this->assertEquals('2017-01-01 10:30:00.000000+0000', $pointInTime->toString());
        $this->assertEquals('2017-01-01 10:30:00.000000+0000', (string) $pointInTime);
    }

    /**
     * @test
     */
    public function creating_from_invalid_input(): void
    {
        $this->expectException(InvalidArgumentException::class);
        PointInTime::fromString('this is invalid');
    }

    /**
     * @test
     */
    public function creating_from_date_time(): void
    {
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uO', '2017-01-01 10:30:00.000000+0000');
        $this->assertNotFalse($dateTime);
        $pointInTime = PointInTime::fromDateTime($dateTime);
        $this->assertEquals($dateTime, $pointInTime->dateTime());
    }
}
